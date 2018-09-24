<?php

namespace Versatile\Core\Http\Controllers;

use Artisan;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Support\LogViewer;

class CompassController extends Controller
{
    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        // Check permission
        Versatile::canOrFail('browse_compass');

        $message = '';
        $activeTab = '';

        if ($request->input('log')) {
            $activeTab = 'logs';
            LogViewer::setFile(base64_decode($request->input('log')));
        }

        if ($request->input('logs')) {
            $activeTab = 'logs';
        }

        if ($request->input('download')) {
            return response()->download(LogViewer::pathToLogFile(base64_decode($request->input('download'))));
        }

        if ($request->has('del')) {
            app('files')->delete(LogViewer::pathToLogFile(base64_decode($request->input('del'))));

            return redirect($request->url() . '?logs=true')->with([
                'message' => __('versatile::compass.commands.delete_success') . ' ' . base64_decode($request->input('del')),
                'alert-type' => 'success',
            ]);
        }

        if ($request->has('delall')) {
            foreach (LogViewer::getFiles(true) as $file) {
                app('files')->delete(LogViewer::pathToLogFile($file));
            }

            return redirect($request->url() . '?logs=true')->with([
                'message' => __('versatile::compass.commands.delete_all_success'),
                'alert-type' => 'success',
            ]);
        }

        $artisanOutput = '';

        if ($request->isMethod('post')) {
            $command = $request->command;
            $args = $request->args;
            $args = (isset($args)) ? ' ' . $args : '';

            try {
                $process = new Process('cd ' . base_path() . ' && php artisan ' . $command . $args);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $artisanOutput = $process->getOutput();

                //$artisanOutput = exec('cd ' . base_path() . ' && php artisan ' . $command . $args);
                // Artisan::call($command . $args);
                // $artisanOutput = Artisan::output();
            } catch (Exception $e) {
                $artisanOutput = $e->getMessage();
            }
            $activeTab = 'commands';
        }

        $logs = LogViewer::all();
        $files = LogViewer::getFiles(true);
        $currentFile = LogViewer::getFileName();

        // get the full list of artisan commands and store the output
        $commands = $this->getArtisanCommands();

        return view('versatile::compass.index', [
            'logs' => $logs,
            'files' => $files,
            'current_file' => $currentFile,
            'active_tab' => $activeTab,
            'commands' => $commands,
            'artisan_output' => $artisanOutput
        ]) ->with($message);
    }

    private function getArtisanCommands()
    {
        Artisan::call('list');

        // Get the output from the previous command
        $artisanOutput = Artisan::output();
        $artisanOutput = $this->cleanArtisanOutput($artisanOutput);
        $commands = $this->getCommandsFromOutput($artisanOutput);

        return $commands;
    }

    private function cleanArtisanOutput($output)
    {

        // Add each new line to an array item and strip out any empty items
        $output = array_filter(explode("\n", $output));

        // Get the current index of: "Available commands:"
        $index = array_search('Available commands:', $output);

        // Remove all commands that precede "Available commands:", and remove that
        // Element itself -1 for offset zero and -1 for the previous index (equals -2)
        $output = array_slice($output, $index - 2, count($output));

        return $output;
    }

    private function getCommandsFromOutput($output)
    {
        $commands = [];

        foreach ($output as $output_line) {
            if (empty(trim(substr($output_line, 0, 2)))) {
                $parts = preg_split('/  +/', trim($output_line));
                $command = (object)['name' => trim(@$parts[0]), 'description' => trim(@$parts[1])];
                array_push($commands, $command);
            }
        }

        return $commands;
    }
}
