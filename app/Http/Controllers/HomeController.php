<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    public $res = [];
    public $ressell = [];
    public function otc()
    {
        $otc_lists = array();
        $cex_lists = array();
        $otcsell_lists = array();
        $cexsell_lists = array();
        $result = array();
        $resultsell = array();
        $http = new GuzzleClient();
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $response = $http->post('https://otc.firefoxotc.com/api/market/list', [
            'form_params' => [
                'crypto_currency'=>'OSC',
                'trade_currency'=>'CNY',
                'is_buy'=>false,
                'offset'=>0,
                'limit'=>8
            ],
            'cookies' =>$jar
        ]);
        $otc = json_decode((string) $response->getBody(), true)['data'];
        if ($otc){
            $otc_lists = $otc['lists'];
        }

        $response1 = $http->get('https://cex.com/Jsons/trade_wkc_usdt.js?v=0.3'.mt_rand()/mt_getrandmax()*(3.0-0.1),[
            'headers' => [
                'Content-Type' => 'application/javascript',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
                'X-Requested-With' => 'XMLHttpRequest',
                'Connection' => 'keep-alive',
                'cookie' => '__jsluid=e65dedad7904e3849b5592ee04dbdc24; PHPSESSID=pd0ub5ndpcm1lq48mk95earc57; UM_distinctid=15fd917892f991-0626c77ae279ae-7b113d-1fa400-15fd9178930a91; __jsl_clearance=1511327058.543|0|l8XSRxw%2Fmujlr012VCZt1qwGjmo%3D; language=zh-CN; CNZZDATA1264498178=1370103603-1511172906-https%253A%252F%252Fcex.com%252F%7C1511325138'
            ],
            'cookies' =>$jar
        ]);
        $cex = json_decode((string) $response1->getBody(), true);

        if ($cex){
            $cex_lists = $cex['depth'][2];
        }

//        $goutteClient = new Client();
//        $guzzleClient = new GuzzleClient(array(
//            'timeout' => 60,
//        ));
//        $goutteClient->setClient($guzzleClient);
//        $goutteClient ->setHeader('cookie',':wanke_cuc=c3c5792aa86ed298abfd57a99f0da3ae; wanke=06497e816ff2b19d50830f54675a1252; pgv_pvi=2628343808; pgv_si=s2473972736; al_tanchuang=5');
//
//        $response3 = $goutteClient ->request('get','https://wanke.wylh.cc/order/sell_order?v=0.3'.mt_rand()/mt_getrandmax()*(3.0-0.1));
//
//        $response3->filter('tr > td')->each(function ($node,$i) {
//            //print $node->text()."\n";
//            $this->res[] = $node->text();
//        });
//        $resultwuyou = $this->res;
//        for($i=0;$i<8;$i++)
//        {
//            $bbb[] = array_slice($resultwuyou, $i * 5 ,5);
//        }

        $res = array_merge($otc_lists,$cex_lists);

        for ($i=0;$i<count($res);$i++){
            if (isset($res[$i]['id'])){
                $result[$i]['name'] = '火狐狸网';
                $result[$i]['min_qty'] = $res[$i]['min_qty'];
                $result[$i]['max_qty'] = $res[$i]['max_qty'];
                $result[$i]['price'] = $res[$i]['price'];
                $result[$i]['url'] = 'https://www.firefoxotc.com';
            }elseif (isset($res[$i]['type'])){
                $result[$i]['name'] = 'cex网';
                $result[$i]['min_qty'] = 0;
                $result[$i]['max_qty'] = $res[$i]['amount'];
                $result[$i]['price'] = $res[$i]['price']*6.6125;
                $result[$i]['url'] = 'https://cex.com/market/wkc_usdt';
            }else{
//                $result[$i]['name'] = '无忧网';
//                $result[$i]['min_qty'] = 0;
//                $result[$i]['max_qty'] = $res[$i][1];
//                $result[$i]['price'] = $res[$i][3];
//                $result[$i]['url'] = 'https://wanke.wylh.cc/order/sell_order';

            }
        }

        foreach($result as $key =>$val) {
            $sortdistance[$key] = $val['price'];
        }

        array_multisort($sortdistance, SORT_ASC, $result);

        $response2 = $http->post('https://otc.firefoxotc.com/api/market/list', [
            'form_params' => [
                'crypto_currency'=>'OSC',
                'trade_currency'=>'CNY',
                'is_buy'=>true,
                'offset'=>0,
                'limit'=>8
            ],
            'cookies' =>$jar
        ]);
        $otcsell = json_decode((string) $response2->getBody(), true)['data'];
        if ($otcsell){
            $otcsell_lists = $otcsell['lists'];
        }
        if ($cex){
            $cexsell_lists = $cex['depth'][1];
        }

//        $goutteClient = new Client();
//        $guzzleClient = new GuzzleClient(array(
//            'timeout' => 60,
//        ));
//        $goutteClient->setClient($guzzleClient);
//        $goutteClient ->setHeader('cookie',':wanke_cuc=c3c5792aa86ed298abfd57a99f0da3ae; wanke=06497e816ff2b19d50830f54675a1252; pgv_pvi=2628343808; pgv_si=s2473972736; al_tanchuang=5');
//
//        $response4 = $goutteClient ->request('get','https://wanke.wylh.cc/order?v=0.3'.mt_rand()/mt_getrandmax()*(3.0-0.1));
//
//        $response4->filter('tr > td')->each(function ($node,$i) {
//            //print $node->text()."\n";
//            $this->ressell[] = $node->text();
//        });
//        $resultwuyousell = $this->ressell;
//        for($i=0;$i<8;$i++)
//        {
//            $bbbsell[] = array_slice($resultwuyousell, $i * 5 ,5);
//        }

        $ressell = array_merge($otcsell_lists,$cexsell_lists);


        for ($i=0;$i<count($ressell);$i++){
            if (isset($ressell[$i]['id'])){
                $resultsell[$i]['name'] = '火狐狸网';
                $resultsell[$i]['min_qty'] = $ressell[$i]['min_qty'];
                $resultsell[$i]['max_qty'] = $ressell[$i]['max_qty'];
                $resultsell[$i]['price'] = $ressell[$i]['price'];
                $resultsell[$i]['url'] = 'https://www.firefoxotc.com';
            }elseif (isset($ressell[$i]['type'])){
                $resultsell[$i]['name'] = 'cex网';
                $resultsell[$i]['min_qty'] = 0;
                $resultsell[$i]['max_qty'] = $ressell[$i]['amount'];
                $resultsell[$i]['price'] = $ressell[$i]['price']*6.6125;
                $resultsell[$i]['url'] = 'https://cex.com/market/wkc_usdt';
            }else{
//                $resultsell[$i]['name'] = '无忧网';
//                $resultsell[$i]['min_qty'] = 0;
//                $resultsell[$i]['max_qty'] = $ressell[$i][1];
//                $resultsell[$i]['price'] = $ressell[$i][3];
//                $resultsell[$i]['url'] = 'https://wanke.wylh.cc/order';
            }
        }

        foreach($resultsell as $key =>$val) {
            $sortdistancesell[$key] = $val['price'];
        }

        array_multisort($sortdistancesell, SORT_DESC, $resultsell);

        $resdata['otc'] = $result;
        $resdata['otcsell'] = $resultsell;

        return $resdata;

    }

    public function cex()
    {
        $http = new GuzzleClient();

        $jar = new \GuzzleHttp\Cookie\CookieJar();

//        $domain = 'cex.com';
//        $cookies = [
//            'PHPSESSID' => 'pd0ub5ndpcm1lq48mk95earc57',
//            //
//        ];
//        $cookieJar = $jar->fromArray($cookies, $domain);
        //dump($cookieJar);
        $response = $http->get('https://cex.com/Jsons/trade_wkc_usdt.js?v=0.3'.mt_rand()/mt_getrandmax()*(3.0-0.1),[
            'headers' => [
                'Content-Type' => 'application/javascript',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
                'X-Requested-With' => 'XMLHttpRequest',
                'Connection' => 'keep-alive',
                //'cookies' => $jar
                'cookie' => '__jsluid=e65dedad7904e3849b5592ee04dbdc24; PHPSESSID=pd0ub5ndpcm1lq48mk95earc57; UM_distinctid=15fd917892f991-0626c77ae279ae-7b113d-1fa400-15fd9178930a91; __jsl_clearance=1511327058.543|0|l8XSRxw%2Fmujlr012VCZt1qwGjmo%3D; language=zh-CN; CNZZDATA1264498178=1370103603-1511172906-https%253A%252F%252Fcex.com%252F%7C1511325138'
            ],
            'cookies' =>$jar
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function wuyou()
    {
        $goutteClient = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $goutteClient->setClient($guzzleClient);
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $goutteClient ->setHeader('cookie',':wanke_cuc=c3c5792aa86ed298abfd57a99f0da3ae; wanke=06497e816ff2b19d50830f54675a1252; pgv_pvi=2628343808; pgv_si=s2473972736; al_tanchuang=5');

        $response5 = $guzzleClient->get('https://wanke.wylh.cc/utils/login_code',[
            'cookies' =>$jar
        ]);
//
        dump($response5->getBody()->getContents());

        $response3 = $guzzleClient->post('https://wanke.wylh.cc/login/check_mobile', [
            'form_params' => [
                'mobile' => '15333619819',
                'code' => '3788',
                'photo_code' => 'gj56',
            ],
            'cookies' =>$jar
        ]);

        dump($response3->getBody()->getContents());

        $response4 = $goutteClient ->request('get','https://wanke.wylh.cc/order/sell_order?v=0.3'.mt_rand()/mt_getrandmax()*(3.0-0.1));
        dd($response4);

        $response4->filter('tr > td')->each(function ($node,$i) {
            //print $node->text()."\n";
            $this->res[] = $node->text();
        });
        $resultwuyou = $this->res;
        for($i=0;$i<8;$i++)
        {
            $bbb[] = array_slice($resultwuyou, $i * 5 ,5);
        }
        return $bbb;
    }
}
