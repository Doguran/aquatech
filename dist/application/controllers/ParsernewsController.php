<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.09.19
 * Time: 7:56
 */

class ParsernewsController implements IController {
    protected $_parserUrl = "http://aquatecnica.ru/news";

    public function indexAction() {

        $allNews = $this->parser($this->_parserUrl);
        if($allNews["http_code"]==200 || $allNews["http_code"]==304){

            $ParsernewsModel = new ParsernewsModel();
            $parseId = $ParsernewsModel->getParseId();

            //Helper::print_arr($parseId); exit;

            include_once("application/simple_html_dom.php");
            $html = str_get_html($allNews['content']);

            foreach($html->find('.omega') as $article) {
                $href = explode("/", $article->find('.post-content a',0)->href);
                if(in_array($href[2], $parseId)) continue;

                $title = trim($article->find('h2',0)->plaintext);
                $anons = trim($article->find('.post-content p',0)->plaintext);
                $pid = $href[2];

                $oneNews = $this->parser($this->_parserUrl."/".$pid);
                if($oneNews["http_code"]==200 || $oneNews["http_code"]==304){
                    $htmlNews = str_get_html($oneNews['content']);
                    $data = trim($htmlNews->find('.meta',0)->plaintext);
                    $post = $htmlNews->find('.post-content',0)->innertext;

                    $ParsernewsModel->insertNews($pid,$title,$anons,$data,$post);

                }


            }



        }


    }
    public function parser( $url ){
        $options = array(
          CURLOPT_RETURNTRANSFER => true,     // return web page
          CURLOPT_HEADER         => false,    // don't return headers
          CURLOPT_FOLLOWLOCATION => true,     // follow redirects
          CURLOPT_ENCODING       => "",       // handle all encodings
          CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0 FirePHP/0.7.4", // who am i
          CURLOPT_AUTOREFERER    => true,     // set referer on redirect
          CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
          CURLOPT_TIMEOUT        => 120,      // timeout on response
          CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
          CURLOPT_SSL_VERIFYPEER => false     //не проверяем скертификат
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;

        /*if($header["http_code"]!=200 || $header["http_code"]!=304){

            //var_dump($header);
            //пишем ошибку в лог
            exit("не удалось спарсить");
        }*/
        return  $header;
    }

}