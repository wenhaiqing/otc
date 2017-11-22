<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class HomeController extends Controller
{
    public $res = [];
    public function otc()
    {
        $otc_lists = array();
        $cex_lists = array();
        $result = array();
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
        $res = array_merge($otc_lists,$cex_lists);

        for ($i=0;$i<count($res);$i++){
            if (isset($res[$i]['id'])){
                $result[$i]['name'] = '火狐狸网';
                $result[$i]['min_qty'] = $res[$i]['min_qty'];
                $result[$i]['max_qty'] = $res[$i]['max_qty'];
                $result[$i]['price'] = $res[$i]['price'];
            }elseif (isset($res[$i]['type'])){
                $result[$i]['name'] = 'cex网';
                $result[$i]['min_qty'] = 0;
                $result[$i]['max_qty'] = $res[$i]['amount'];
                $result[$i]['price'] = $res[$i]['price']*6.6125;
            }else{

            }
        }

        foreach($result as $key =>$val) {
            $sortdistance[$key] = $val['price'];
        }

        array_multisort($sortdistance, SORT_ASC, $result);


        return $result;

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

        $response = $goutteClient ->request('get','https://wanke.wylh.cc/order/sell_order',[
            'headers' => [
                'Content-Type' => 'application/html',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
            ]
        ]);

        $response->filter('tr > td')->each(function ($node,$i) {
            //print $node->text()."\n";
            $this->res[] = $node->text();
        });
        $result = $this->res;
        for($i=0;$i<8;$i++)
        {
            $bbb[] = array_slice($result, $i * 5 ,5);
        }
        return $bbb;
    }
}
