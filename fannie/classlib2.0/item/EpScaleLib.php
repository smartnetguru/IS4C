<?php
/*******************************************************************************

    Copyright 2014 Whole Foods Co-op, Duluth, MN

    This file is part of CORE-POS.

    IT CORE is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    IT CORE is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    in the file license.txt along with IT CORE; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*********************************************************************************/

namespace COREPOS\Fannie\API\item {

class EpScaleLib 
{
    /* CSV fields for WriteOneItem & ChangeOneItem records
       Required does not mean you *have to* specify a value,
       but the default will be included if you omit that field.
       Non-required fields won't be sent to the scale at all
       unless specified by the caller
    */
    private static $WRITE_ITEM_FIELDS = array(
        'RecordType' => array('name'=>'Record Type', 'required'=>true, 'default'=>'ChangeOneItem'),
        'PLU' => array('name'=>'PLU Number', 'required'=>true, 'default'=>'0000'),
        'Description' => array('name'=>'Item Description', 'required'=>false, 'default'=>'', 'quoted'=>true),
        'ReportingClass' => array('name'=>'Reporting Class', 'required'=>true, 'default'=>'999999'),
        'Label' => array('name'=>'Label Type 01', 'required'=>false, 'default'=>'53'),
        'Tare' => array('name'=>'Tare 01', 'required'=>false, 'default'=>'0'),
        'ShelfLife' => array('name'=>'Shelf Life', 'required'=>false, 'default'=>'0'),
        'Price' => array('name'=>'Price', 'required'=>true, 'default'=>'0.00'),
        'ByCount' => array('name'=>'By Count', 'required'=>false, 'default'=>'0'),
        'Type' => array('name'=>'Item Type', 'required'=>true, 'default'=>'Random Weight'),
        'NetWeight' => array('name'=>'Net Weight', 'required'=>false, 'default'=>'0'),
        'Graphics' => array('name'=>'Graphics Number', 'required'=>false, 'default'=>'0'),
    );

    static private $NL = "\r\n";

    /**
      Generate CSV line for a given item
      @param $item_info [keyed array] of value. Keys correspond to WRITE_ITEM_FIELDS
      @return [string] CSV formatted line
    */
    static public function getItemLine($item_info)
    {
        if ($item_info['RecordType'] == 'ChangeOneItem') {
            return self::getAddItemLine($item_info);
        } else {
            return self::getUpdateItemLine($item_info);
        }

        return $line;
    }

    static private function getAddItemLine($item_info)
    {
        $line = 'CCOSPIA' . chr(253);
        $line .= 'PNO' . $item_info['PLU'] . chr(253);
        $line .= 'UPC' . '2' . str_pad($item_info['PLU'],4,'0',STR_PAD_LEFT) . '000000' . chr(253);
        $line .= 'DN1' . (isset($item_info['Description']) ? $item_info['Description'] : '') . chr(253);
        $line .= 'DS1' . '0' . chr(253);
        $line .= 'DN2' . chr(253);
        $line .= 'DS2' . '0' . chr(253);
        $line .= 'DN3' . chr(253);
        $line .= 'DS3' . '0' . chr(253);
        $line .= 'DN4' . chr(253);
        $line .= 'DS4' . '0' . chr(253);
        $line .= 'UPR' . (isset($item_info['Price']) ? floor(100*$item_info['Price']) : '0') . chr(253);
        $line .= 'EPR' . '0' . chr(253);
        $line .= 'FWT' . (isset($item_info['NetWeight']) ? $item_info['NetWeight'] : '0') . chr(253);
        if ($item_info['Type'] == 'Random Weight') {
            $line .= 'UMELB' . chr(253);
        } else {
            $line .= 'UMEFW' . chr(253);
        }
        $line .= 'BCO' . '0' . chr(253);
        $line .= 'WTA' . '0' . chr(253);
        $line .= 'UWT' . (isset($item_info['Tare']) ? floor(100*$item_info['Tare']) : '0') . chr(253);
        $line .= 'SLI' . (isset($item_info['ShelfLife']) ? $item_info['ShelfLife'] : '0') . chr(253);
        $line .= 'SLT' . '0' . chr(253);
        $line .= 'EBY' . '0' . chr(253);
        $line .= 'CCL' . (isset($item_info['ReportingClass']) ? $item_info['ReportingClass'] : '0') . chr(253);
        $line .= 'LNU' . '0' . chr(253);
        $line .= 'GNO' . (isset($item_info['Graphics']) ? str_pad($item_info['Graphics'],6,'0',STR_PAD_LEFT) : '0') . chr(253);
        $line .= 'GNU' . '0' . chr(253);
        $line .= 'MNO' . '0' . chr(253);
        $line .= 'INO' . $item_info['PLU'] . chr(253);
        $line .= 'TNO' . '0' . chr(253);
        $line .= 'NTN' . '0' . chr(253);
        $line .= 'NRA' . '95' . chr(253);
        $line .= 'ANO' . '0' . chr(253);
        $line .= 'FTA' . 'N' . chr(253);
        $line .= 'LF1' . (isset($item_info['Label']) ? $item_info['Label'] : '0') . chr(253);
        $line .= 'LF2' . '0' . chr(253);
        $line .= 'FR1' . '0' . chr(253);
        $line .= 'FDT' . '0' . chr(253);
        $line .= 'PTA' . '0' . chr(253);
        $line .= 'PC1' . chr(253);
        $line .= 'EAS' . '0' . chr(253);
        $line .= 'FSL' . 'N' . chr(253);
        $line .= 'FUB' . 'N' . chr(253);
        $line .= 'UF1' . chr(253);
        $line .= 'UF2' . chr(253);
        $line .= 'UF3' . chr(253);
        $line .= 'UF4' . chr(253);
        $line .= 'UF5' . chr(253);
        $line .= 'UF6' . chr(253);
        $line .= 'UF7' . chr(253);
        $line .= 'UF8' . chr(253);
        $line .= 'PTN' . '1' . chr(253);

        return $line;
    }

    static private function getUpdateItemLine($item_info)
    {
        $line = 'CCOSPIC' . chr(253); 
        foreach (self::$WRITE_ITEM_FIELDS as $key => $field_info) {
            if (isset($item_info[$key])) {
                switch ($key) {
                    case 'PLU':
                        $line .= 'PNO' . $item_info[$key] . chr(253);
                        break;
                    case 'Description':
                        $line .= 'DN1' . $item_info[$key] . chr(253);
                        break;
                    case 'ReportingClass':
                        $line .= 'CCL' . $item_info[$key] . chr(253);
                    case 'Label':
                        $line .= 'FL1' . $item_info[$key] . chr(253);
                        break;
                    case 'Tare':
                        $line .= 'UTA' . floor(100*$item_info[$key]) . chr(253);
                        break;
                    case 'ShelfLife':
                        $line .= 'SLI' . $item_info[$key] . chr(253) . 'SLT0' . chr(253);
                        break;
                    case 'Price':
                        $line .= 'UPR' . floor(100*$item_info[$key]) . chr(253);
                        break;
                    case 'Type':
                        if ($item_info[$key] == 'Random Weight') {
                            $line .= 'UMELB' . chr(253);
                        } else {
                            $line .= 'UMEFW' . chr(253);
                        }
                        break;
                    case 'NetWeight':
                        $line .= 'FWT' . $item_info[$key] . chr(253);
                        break;
                    case 'Graphics':
                        $line .= 'GNO' . str_pad($item_info[$key],6,'0',STR_PAD_LEFT) . chr(253);
                        break;
                }
            }
        }

        return $line;
    }

    /**
      Write item update file(s) to ePlum
      @param $items [keyed array] of values. Keys correspond to WRITE_ITEM_FIELDS
        $items may also be an array of keyed arrays to write multiple items
        One additional key, ExpandedText, is used to write Expanded Text. This
        is separate from the Write Item operation so it's excluded from that
        set of fields.
      @param $scales [keyed array, optional] List of scales items will be written to
        Must have keys "host", "type", and "dept". 
        May have boolean value with key "new".
    */
    static public function writeItemsToScales($items, $scales=array())
    {
        $config = \FannieConfig::factory(); 
        if (!isset($items[0])) {
            $items = array($items);
        }
        $new_item = false;
        if (isset($items[0]['RecordType']) && $items[0]['RecordType'] == 'WriteOneItem') {
            $new_item = true;
        }
        $header_line = '';
        $file_prefix = self::sessionKey();
        $output_dir = realpath(dirname(__FILE__) . '/../../item/hobartcsv/csvfiles');
        $selected_scales = $scales;
        if (!is_array($scales) || count($selected_scales) == 0) {
            $selected_scales = $config->get('SCALES');
        }
        $scale_model = new \ServiceScalesModel(\FannieDB::get($config->get('OP_DB')));
        $i = 0;
        foreach ($selected_scales as $scale) {
            $scale_model->reset();
            $scale_model->host($scale['host']);

            $file_name = sys_get_temp_dir() . '/' . $file_prefix . '_writeItem_' . $i . '.dat';
            $fp = fopen($file_name, 'w');
            fwrite($fp, 'BNA' . $file_prefix . '_' . $i . chr(253) . self::$NL);
            foreach($items as $item) {
                $item_line = self::getItemLine($item);
                if ($scale_model->epStoreNo() != 0) {
                    $item_line .= 'SNO' . $scale_model->epStoreNo() . chr(253);
                }
                $item_line .= 'DNO' . $scale_model->epDeptNo() . chr(253);
                $item_line .= 'SAD' . $scale_model->epScaleAddress() . chr(253);
                $item_line .= self::$NL;
                fwrite($fp, $item_line);

                if (isset($item['ExpandedText'])) {
                    $et_line = ($new_item ? 'CCOSIIA' : 'CCOSIIC') . chr(253);
                    if ($scale_model->epStoreNo() != 0) {
                        $et_line .= 'SNO' . $scale_model->epStoreNo() . chr(253);
                    }
                    $et_line .= 'DNO' . $scale_model->epDeptNo() . chr(253);
                    $et_line .= 'SAD' . $scale_model->epScaleAddress() . chr(253);
                    $et_line .= 'PNO' . $item['PLU'] . chr(253);
                    $et_line .= 'INO' . $item['PLU'] . chr(253);
                    $et_line .= 'ITE' . $item['ExpandedText'] . chr(253);
                    $et_line .= self::$NL;
                    fwrite($fp, $et_line);
                }
            }
            fclose($fp);

            // move to DGW; cleanup the file in the case of failure
            if (!rename($file_name, $output_dir . '/' . basename($file_name))) {
                unlink($file_name);
            }

            $i++;
        }
    }

    /**
      Delete item(s) from scale
      @param $items [string] four digit PLU 
        or [array] of [string] 4 digit PLUs
    */
    static public function deleteItemsFromScales($items, $scales=array())
    {
        $config = \FannieConfig::factory(); 

        if (!is_array($items)) {
            $items = array($items);
        }

        $file_prefix = self::sessionKey();
        $output_dir = realpath(dirname(__FILE__) . '/../../item/hobartcsv/csvfiles');
        $selected_scales = $scales;
        if (!is_array($scales) || count($selected_scales) == 0) {
            $selected_scales = $config->get('SCALES');
        }
        $scale_model = new \ServiceScalesModel(\FannieDB::get($config->get('OP_DB')));
        $i = 0;
        foreach ($selected_scales as $scale) {
            $file_name = sys_get_temp_dir() . '/' . $file_prefix . '_deleteItem_' . $i . '.dat';
            $fp = fopen($file_name, 'w');
            foreach ($items as $plu) {
                if (strlen($plu) !== 4) {
                    // might be a UPC
                    $upc = str_pad($plu, 13, '0', STR_PAD_LEFT);
                    if (substr($upc, 0, 3) != '002') {
                        // not a valid UPC either
                        continue;
                    }
                    preg_match("/002(\d\d\d\d)0/",$upc,$matches);
                    $plu = $matches[1];
                }
            }
            fclose($fp);

            // move to DGW dir
            if (!rename($file_name, $output_dir . '/' . basename($file_name))) {
                unlink($file_name);
            }

            $i++;
        }
    }

    /**
      Get attributes for a given label number
      @param $label_number [integer]
      @return keyed array
        - align => vertical or horizontal
        - fixed_weight => boolean
        - graphics => boolean
    */
    static public function labelToAttributes($label_number)
    {
        $ret = array(
            'align' => 'vertical',
            'fixed_weight' => false,
            'graphics' => false,
        );
        switch ($label_number) {
            case 23:
                $ret['fixed_weight'] = true;
                break;
            case 53:
                $ret['graphics'] = true;
                break;
            case 63:
                $ret['fixed_weight'] = true;
                $ret['align'] = 'horizontal';
                break;
            case 103:
                break;
            case 113:
                $ret['align'] = 'horizontal';
                break;
        }

        return $ret;
    }

    /**
      Get appropriate label number for given attributes
      @param $align [string] vertical or horizontal
      @param $fixed_weight [boolean, default false]
      @param $graphics [boolean, default false]
      @return [integer] label number
    */
    static public function attributesToLabel($align, $fixed_weight=false, $graphics=false)
    {
        if ($graphics) {
            return 53;
        }

        if ($align == 'horizontal') {
            return ($fixed_weight) ? 63 : 133;
        } else {
            return ($fixed_weight) ? 23 : 103;
        }
    }

    static private function scalePluToUpc($plu)
    {
        // convert PLU to UPC
        // includes WFC oddities with zero alignment
        // on short PLUs (less than 4 digits)
        $upc = str_pad($plu, 3, '0', STR_PAD_LEFT);
        $upc = str_pad($upc, 4, '0', STR_PAD_LEFT);
        $upc = '002' . $upc . '000000';

        return $upc;
    }

    static private function sessionKey()
    {
        $session_key = '';
        for ($i = 0; $i < 20; $i++) {
            $num = rand(97,122);
            $session_key = $session_key . chr($num);
        }

        return $session_key;
    }

    static public function scaleOnline($host, $port=6000)
    {
        $s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($s, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>2, 'usec'=>0));
        socket_set_option($s, SOL_SOCKET, SO_SNDTIMEO, array('sec'=>2, 'usec'=>0));
        if (socket_connect($s, $host, $port)) {
            socket_close($s);
            return true;
        } else {
            return false;
        }
    }
}

}

namespace {
    class EpScaleLib extends \COREPOS\Fannie\API\item\EpScaleLib {}
}
