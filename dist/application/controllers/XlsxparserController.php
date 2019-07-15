<?php


class XlsxparserController implements IController {

    protected $_row_parse = 8;   //на  сколько столбцов парсить
    //protected $_col_parse = 845;   //на сколько строк
    protected $_path_to_xl = 'exel/';  //путь до папки xl
    protected $_cell_img = 3; //в каком столбце картинка считаем с 0
    protected $_path_to_out_img = 'imgProduct/'; //путь до папки куда складывать картинки
    protected $_sharedStringsArray = array();



    public function __construct(){

        if(!ADMIN)
            throw new Exception("Нет доступа");

    }


    /**
     * отделяет буквы от цифр
     *
     * @param string $str
     *
     * @return string
     */
    private function _separateLetters($str) {
        $letter = "";
        for ($i = 0; $i < strlen($str); $i++) {
            if (!is_numeric($str[$i])) {
                $letter .= $str[$i];
            }
        }
        return $letter;
    }


    //преобразовываем в массив значениия строк из sharedStrings.xml
    private function _sharedStringsToArray(){

        $xml = simplexml_load_file($this->_path_to_xl.'xl/sharedStrings.xml');
        $sharedStringsArr = array();
        foreach ($xml->children() as $item) {

            if ((string) $item->t == "") {

                $t = "";
                foreach ($item->r AS $tt) {

                    $t .= $tt->t;
                }
            }else{
                $t = (string) $item->t;
            }
            $sharedStringsArr[] = $t;
        }
        return $this->_sharedStringsArray = $sharedStringsArr;
    }

    //unset($xml);

    public function parserXslxAllSheets() {
        $workbookXml = simplexml_load_file($this->_path_to_xl.'xl/workbook.xml');
        //преобразовываем в массив id и имя листа workbook.xml
        $workbookArr = [];
        foreach ($workbookXml->sheets->children() as $item) {
            $namespaces                   = $item->getNameSpaces(TRUE);
            $a                            = $item->attributes($namespaces['r']);
            $workbookArr[(string) $a->id] = (string) $item->attributes()->name;
        }
        return $workbookArr;
    }

    //unset($workbookXml);


    public function parserXslxSheet($rId){

        $this->_sharedStringsToArray();

        $workbookXml_rels = simplexml_load_file($this->_path_to_xl.'xl/_rels/workbook.xml.rels');
        $ns               = $workbookXml_rels->getNameSpaces()[""];
        $workbookXml_rels->registerXPathNamespace('sheet', $ns);
        $toSheetXmlPath = (string) $workbookXml_rels->xpath('./sheet:Relationship[@Id="'
                                                            . $rId . '"]/@Target')[0];
        unset($workbookXml_rels);
        $sheetXml = simplexml_load_file($this->_path_to_xl.'xl/' . $toSheetXmlPath);

        //узнаем есть ли картинки, если да - загружаем drawing
        $drawingTag = $sheetXml->drawing;
        if ($drawingTag) {
            $namespaces         = $sheetXml->getNameSpaces(TRUE);
            $a                  = $sheetXml->drawing->attributes($namespaces['r']);
            $drawingrId         = (string) $a->id;
            $worksheetsXml_rels = simplexml_load_file($this->_path_to_xl.'xl/worksheets/_rels/'
                                                      . basename($toSheetXmlPath)
                                                      . '.rels');
            $ns                 = $worksheetsXml_rels->getNameSpaces()[""];
            $worksheetsXml_rels->registerXPathNamespace('dr', $ns);
            $toDrawingXmlPath
                        = (string) $worksheetsXml_rels->xpath('./dr:Relationship[@Id="'
                                                              . $drawingrId
                                                              . '"]/@Target')[0];
            $drawingXml = simplexml_load_file($this->_path_to_xl.'xl/drawings/'
                                              . basename($toDrawingXmlPath),
              'SimpleXMLElement', 0, 'xdr', TRUE);
            $imgArr     = [];
            foreach ($drawingXml->twoCellAnchor AS $item) {
                if (!(bool) $item->pic) {
                    continue;
                }
                $ns            = $item->pic->blipFill->getNameSpaces(TRUE);
                $img_rId
                               = (string) $item->pic->blipFill->children($ns['a'])->blip->attributes($ns['r'])->embed;
                $drawings_rels = simplexml_load_file($this->_path_to_xl.'xl/drawings/_rels/'
                                                     . basename($toDrawingXmlPath)
                                                     . '.rels');
                $ns            = $drawings_rels->getNameSpaces()[""];
                $drawings_rels->registerXPathNamespace('img', $ns);
                $imgPath = (string) $drawings_rels->xpath('./img:Relationship[@Id="'
                                                          . $img_rId . '"]/@Target')[0];

                foreach (range($item->from->row, $item->to->row) AS $val) {
                    $newName = Helper::generateString() . '.'
                               . pathinfo($imgPath, PATHINFO_EXTENSION);
                    copy ($this->_path_to_xl.'xl/media/'.basename($imgPath), $this->_path_to_out_img.$newName);
                    $imgArr[$val]['img'][] = $newName;
                };

            }
            //var_dump($imgArr);

        }

        $out = [];
        $row = 0;
        foreach ($sheetXml->sheetData->row as $item) {
            if ($row < 3) {
                $row++;
                continue;
            }//начинаем с 4 строки

            $out[$row] = [];
            //по каждой ячейке строки

            $letters = Helper::createLetterRange(9);
            $cell    = 0;
            foreach ($item as $child) {
                $attr  = $child->attributes();
                $value = isset($child->v) ? (string) $child->v : FALSE;
                //если строка записана в прямо самой ячейке в теге is
                $value = isset($child->is) ? (string) $child->is : $value;

                $l = $this->_separateLetters((string) $attr['r']);

                if ($l != $letters[$cell]) {

                    $key1 = array_search($l, $letters);
                    $key2 = array_search($letters[$cell], $letters);
                    //echo "key ".$key1." - key2 ".$key2."<br>";

                    for ($i = 0; $i < ($key1 - $key2); $i++) {
                        // вставляем пустую строку
                        $out[$row][$cell] = FALSE;
                        $cell++;
                        if ($cell > $this->_row_parse) {
                            break;
                        }
                    }

                }
                else {
                    $out[$row][$cell] = isset($attr['t']) ? $this->_sharedStringsArray[$value]
                      : $value;
                    $cell++;
                    if ($cell > $this->_row_parse) {
                        break;
                    }
                }


            }
            $row++;
//            if ($row > $this->_col_parse) {
//                break;
//            }
        }
        unset($drawingXml, $sheetXml);

        //собираем итоговый массив
        foreach ($out as $key => &$val) {
            if (isset($imgArr[$key])) {
                $val[$this->_cell_img] = serialize($imgArr[$key]);
            }
        }
        unset($val, $imgArr);
        return $out;

    }

}