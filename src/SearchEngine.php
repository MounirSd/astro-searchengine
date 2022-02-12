<?php

namespace Astro;
use ErrorException;
use simple_html_dom;

include('lib/simple_html_dom.php');

class SearchEngine
{
  private $searchEngine;

    
  /**
   * setEngine - Either google.com or google.ae
   * 
   * @param  String $searchEngine
   * @return void
   */
  public function setEngine($searchEngine)
  {
    if ($searchEngine !== 'google.ae' && $searchEngine !== 'google.com') {
      throw new ErrorException('Caught exception: Search engine can only be google.ae or google.com');
    }
    $this->searchEngine = $searchEngine;
    return $this;
  }
  
  /**
   * search - Gets Google search results from page 0 to page 5
   *
   * @param  String[] $keywords
   * @return ArrayIterator results
   */
  public function search($keywords)
  {
    $arr = array();
    $html = array();
    for ($i = 0; $i < count($keywords); $i++) {

      $encodedKeyword = urlencode($keywords[$i]);

      $html[$i] = file_get_html('https://www.' . $this->searchEngine . '/search?q=' . $encodedKeyword . '&hl=en&start=0&num=50');



      foreach ($html[$i]->find('div.ZINbbc.luh4tb.O9g5cc.uUPGi') as $index => $ele) {
        $searchEle = new simple_html_dom();
        $searchEle->load($ele->innertext);
        $promotedEle =  $searchEle->find('span.CnP9N.U3A9Ac.qV8iec', 0);
        $promoted = 0;

        if ($promotedEle) {
          $promoted = $searchEle->find('span.CnP9N.U3A9Ac.qV8iec', 0)->plaintext == 'Ad' ? 1 : 0;
        }


        if (!$promoted) {
          $title = html_entity_decode($searchEle->find('div.BNeawe.vvjwJb.AP7Wnd', 0)->plaintext);
          $url = parseUrl($searchEle->find('div.BNeawe.UPmit.AP7Wnd', 0)->plaintext);
          $description = html_entity_decode($searchEle->find('div.BNeawe.s3v9rd.AP7Wnd', 0)->plaintext);
          $result = array('keyword' => $keywords[$i], 'title' => $title, 'url' => $url, 'description' => $description, 'promoted' => $promoted, 'rank' => $index, 'page' => (int)($index / 10) + 1);
          array_push($arr, $result);
        } else {
          $title = html_entity_decode($searchEle->find('div.CCgQ5.MUxGbd.v0nnCb.aLF0Z.OSrXXb>span', 0)->plaintext);
          $url = parseUrl($searchEle->find('span.qzEoUe', 0)->plaintext);
          $description = html_entity_decode($searchEle->find('div.MUxGbd.yDYNvb.lyLwlc.aLF0Z.OSrXXb>div', 0)->plaintext);
          $result = array('keyword' => $keywords[$i], 'title' => $title, 'url' => $url, 'description' => $description, 'promoted' => $promoted, 'rank' => $index, 'page' => (int)($index / 10) + 1);
          array_push($arr, $result);
        }
      }
    }
    return $arr;
  }
}

/**
 * parseUrl - Parsing results url
 *
 * @param  String $url
 * @return String
 */
function parseUrl($url)
{
  $url = str_replace('&#8250;', '/', $url);
  $url = html_entity_decode($url);
  $url = preg_replace('/\s/', '', $url);
  return $url;
}


