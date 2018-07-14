<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  ============================================================================== 
 *  Author	: CloudNET Team
 *  Email	: icloud.erp@gmail.com 
 *  For		: PHP QR Code
 *  Web		: http://phpqrcode.sourceforge.net
 *  License	: open source (LGPL)
 *  ============================================================================== 
 */
require_once APPPATH . "/third_party/phpqrcode/qrlib.php";

class Phpqrcode
{

    public function generate($params = array())
    {
        if (isset($params['black']) && is_array($params['black']) && count($params['black']) == 3 && array_filter($params['black'], 'is_int') === $params['black']) {
            QRimage::$black = $params['black'];
        }
        if (isset($params['white']) && is_array($params['white']) && count($params['white']) == 3 && array_filter($params['white'], 'is_int') === $params['white']) {
            QRimage::$white = $params['white'];
        }
        $params['data'] = (isset($params['data'])) ? $params['data'] : 'QR Code Library';
        if (isset($params['savename'])) {
            $level = 'L';
            if (isset($params['level']) && in_array($params['level'], array('L', 'M', 'Q', 'H')))
                $level = $params['level'];
            $size = 4;
            if (isset($params['size']))
                $size = min(max((int)$params['size'], 1), 10);
            QRcode::png($params['data'], $params['savename'], $level, $size, 2);
            return $params['savename'];
        } else {
            $level = 'L';
            if (isset($params['level']) && in_array($params['level'], array('L', 'M', 'Q', 'H')))
                $level = $params['level'];
            $size = 4;
            if (isset($params['size']))
                $size = min(max((int)$params['size'], 1), 10);
            QRcode::png($params['data'], NULL, $level, $size, 2);
        }
    }

}
