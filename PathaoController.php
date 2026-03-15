<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PathaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createPercel(Request $request)
    {
        $data=[
            'store_id'=>"<store id>",
            'merchant_order_id'=>"<merchant order id>",
            'sender_name'=>"<sender name>",
            'sender_phone'=>"<sender phone>",
            'recipient_name'=>"<recipient name>",
            'recipient_phone'=>"<recipient phone>",
            'recipient_address'=>"<recipient address>",
            'recipient_city'=>"<recipient city>",
            'recipient_zone'=>"<recipient zone>",
            'recipient_area'=>"<recipient area>",
            'delivery_type'=>"<delivery type>",
            'item_type'=>"<item type>",
            'special_instruction'=>"<special instruction>",
            'item_quantity'=>"<item quantity>",
            'item_weight'=>"<item weight>",
            'amount_to_collect'=>"<amount to collect>",
            'item_description'=>"",
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            // CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json",
                "Authorization:Bearer ".@$this->get_access()->access_token,
            ),
        ));

        $response=curl_exec($curl);
        $areas=json_decode($response);
        if(@$areas->data->data == null):
            return [];
        else:
            return @$areas->data->data;
        endif;
    }

    public function get_access()
    {
        $curl = curl_init();

        $token=[
            'client_id'=>'openZQpe7A',
            'client_secret'=>'P9Exsg0FxUxuBltpJd4MKlD38e7pXe4X5GVo2j8g',
            'username'=>'eliasmd339@gmail.com',
            'password'=>'lnfDBP9pc',
            'grant_type'=>'password',

        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($token),
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json"
            ),
        ));

        $token_response=curl_exec($curl);
        $authorization=json_decode($token_response);
        return curl_exec($curl);
    }

    public function get_store()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            // CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json",
                "Authorization:Bearer ".@$this->get_access()->access_token,
            ),
        ));

        $response=curl_exec($curl);
        $store=json_decode($response);
        if(@$store->data->data == null):
            return [];
        else:
            return @$store->data->data;
        endif;
    }

    public function getCities()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            // CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json",
                "Authorization:Bearer ".@$this->get_access()->access_token,
            ),
        ));

        $response=curl_exec($curl);
        $cities=json_decode($response);
        if(@$cities->data->data == null):
            return [];
        else:
            return @$cities->data->data;
        endif;
    }

    public function getZones($city_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            // CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json",
                "Authorization:Bearer ".@$this->get_access()->access_token,
            ),
        ));

        $response=curl_exec($curl);
        $zones=json_decode($response);
        if(@$zones->data->data == null):
            return [];
        else:
            return @$zones->data->data;
        endif;
    }

    public function getAreas($zone_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/orders/rabiulislam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            // CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control:no-cache",
                "content-type:application/json",
                "Authorization:Bearer ".@$this->get_access()->access_token,
            ),
        ));

        $response=curl_exec($curl);
        $areas=json_decode($response);
        if(@$areas->data->data == null):
            return [];
        else:
            return @$areas->data->data;
        endif;
    }



}
