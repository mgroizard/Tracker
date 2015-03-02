<?php

namespace Aleste\TrackerBundle\Twig;

class CustomFiltersExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('popover', array($this, 'popoverFilter'), array('pre_escape' => 'html', 'is_safe' => array('html'))),
            new \Twig_SimpleFilter('strpad', array($this, 'strpadFilter')),
        );
    }

    public function popoverFilter($texto, $limit = 20, $title = "")
    {
        if (strlen($texto) > $limit) {
            if (false !== ($breakpoint = strpos($texto, " ", $limit))) {
                if ($breakpoint < strlen($texto) - 1) {
                    $textoCorto = substr($texto, 0, $breakpoint);

                    return $textoCorto.'&nbsp;<button type="button" class="btn btn-default btn-xs popover-dismiss" data-toggle="popover" title="'.$title.'" data-content="'.$texto.'"><i class="fa fa-ellipsis-h"></i></button>';
                } else {
                    return $texto;
                }
            }

        }

        return $texto;

    }

    public function strpadFilter($input, $padlength, $padstring = '0', $padtype = STR_PAD_LEFT)
    {
         return str_pad($input, $padlength, $padstring, $padtype);
    }

    public function getName()
    {
        return 'aleste_custom_filters_extension';
    }
}
