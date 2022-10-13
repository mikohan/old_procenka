<?php
include_once ('simple_html_dom.php');

class ParserClass extends Conn
{

    public function searchZapkia($cat)
    {
        $html = new simple_html_dom();
        $url = 'http://zapkia.ru/fine.html?name=' . $cat . '&rname_text=' . $cat;
        $html = file_get_html($url);
        $parse = [];
        foreach ($html->find('a.tov b') as $k => $f) {
            $name = $f->plaintext;
            $pattern = '/[\dA-Z]{10}/';
            if (preg_match($pattern, $name, $mat) == 0) {
                $pattern = '/[\dA-Z-]{11}/';
                preg_match($pattern, $name, $mat);
            }
            @$cat = str_replace('-', '', $mat[0]);
            $parse[] = array(
                $name,
                $cat,
                @$html->find('.textcen1 b')[$k]->plaintext,
                'Запкиа'
            );
        }
        $html->clear();
        unset($html);
        return $parse;
    }

    public function searchLider($cat)
    {
        $html = new simple_html_dom();
        $url = 'https://zaptop.ru/products?keyword=' . $cat;
        $html = file_get_html($url);
        $parse = [];
        foreach ($html->find('.product') as $k => $f) {
            @$cat = str_replace('-', '', $f->find('.feature-value')[0]->plaintext);
            @$name = trim($f->find('h2',0)->plaintext);
            @$price = str_replace(' ','', $html->find('.price')[$k]->plaintext);
            @$parse[] = array(
                $name,
                $cat,
                $price,
                'ЛидерЗап'
                
            );
        }
        $html->clear();
        unset($html);
        return $parse;
    }
    
    public function searchGlobal($cat)
    {
        $html = new simple_html_dom();
        $url = 'http://dvsavto.ru/search/index.php?q=' . $cat;
        $html = file_get_html($url);
        $parse = [];
        foreach ($html->find('.element-name a') as $k => $f) {
            //p($f->href);
            $ur = 'http://dvsavto.ru' . $f->href;
            $htm = file_get_html($ur);
            @$name = $htm->find('#pagetitle',0)->plaintext;
            @$cat = $htm->find('b',0)->plaintext;
            @$price = str_replace(' руб', '', $htm->find('.catalog-price',0)->plaintext);
            @$price = str_replace(' ', '', $price);
            $parse[] = array($name, $cat, $price, 'Глобал');
           // p($cat);
        }
        $html->clear();
        unset($html);
        return $parse;
    }
}