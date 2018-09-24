<?php

namespace Versatile\Core\Components\ContentTypes;

class Password extends BaseType
{
    /**
     * Handle password fields.
     *
     * @return mixed|null|string
     */
    public function handle()
    {
        if (empty($this->request->input($this->row->field))) {
            return null;
        }

        return bcrypt($this->request->input($this->row->field));
    }
}
