<?php

class View
{
    function generate($content_view, $template_view, $data = null)
    {
        require 'app/views/'.$template_view;
    }
}
