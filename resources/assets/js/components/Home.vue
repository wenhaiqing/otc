<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div data-v-45e29ae5="" data-v-b07140c2="" class="trade_buy">
                        <div data-v-45e29ae5="" class="trade_buy_header">
                           卖方发布
                        </div>
                        <div data-v-45e29ae5="" class="trade_buy_title">
                            <div data-v-45e29ae5="" class="el-row">
                                <div data-v-45e29ae5="" class="trade_buy_title_item user el-col el-col-5">平台名</div>
                                <div data-v-45e29ae5="" class="trade_buy_title_item limit el-col el-col-4">数量</div>
                                <div data-v-45e29ae5="" class="trade_buy_title_item price el-col el-col-4">价格</div>
                            </div>
                        </div>

                        <div data-v-45e29ae5="" class="trade_list" v-for="otc in otcs" :key="otc.id">
                            <div data-v-45e29ae5="" class="trade_buy_item el-row">
                                <div data-v-45e29ae5="" class="trade_buy_col user el-col el-col-5">
                                    {{otc.name}}
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col limit el-col el-col-3">
                                    {{otc.min_qty}}-{{otc.max_qty}} OSC
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col price el-col el-col-3">
                                    {{otc.price}} CNY
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col button_group el-col el-col-4">
                                    <div data-v-45e29ae5="" class="buy"><a href="https://otc.firefoxotc.com">购买</a></div>
                                </div>
                            </div>
                        </div>

                        <div data-v-45e29ae5="" class="trade_list" v-for="wuyou in wuyous">
                            <div data-v-45e29ae5="" class="trade_buy_item el-row">
                                <div data-v-45e29ae5="" class="trade_buy_col user el-col el-col-5">
                                    wanke.wylh.cc
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col limit el-col el-col-3">
                                    {{wuyou[1]}} OSC
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col price el-col el-col-3">
                                    {{wuyou[3]}} CNY
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col button_group el-col el-col-4">
                                    <div data-v-45e29ae5="" class="buy"><a href="https://wanke.wylh.cc/order/sell_order">购买</a></div>
                                </div>
                            </div>
                        </div>

                        <div data-v-45e29ae5="" class="trade_list" v-for="cex in cexs[1]">
                            <div data-v-45e29ae5="" class="trade_buy_item el-row">
                                <div data-v-45e29ae5="" class="trade_buy_col user el-col el-col-5">
                                    cex.com
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col limit el-col el-col-3">
                                    {{cex.amount}} OSC
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col price el-col el-col-3">
                                    {{cex.price*6.6125}} CNY
                                </div>
                                <div data-v-45e29ae5="" class="trade_buy_col button_group el-col el-col-4">
                                    <div data-v-45e29ae5="" class="buy"><a href="https://cex.com">购买</a></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.loadding = true;
            let formData={
                crypto_currency:'OSC',
                trade_currency:'CNY',
                is_buy:false,
                offset:0,
                limit:8
            }
            axios.get('http://otc.zdxinfo.com/api/otc',formData,{
                headers: {'X-Requested-With': 'XMLHttpRequest','Access-Control-Allow-Origin':'*'},
            }).then(response=>{
                //console.log(response.data);
                this.loadding = false;
                this.otcs = response.data
            })

//            axios.get('http://otc.xyz/api/wuyou',formData,{
//                headers: {'X-Requested-With': 'XMLHttpRequest','Access-Control-Allow-Origin':'*'},
//            }).then(response=>{
//                console.log(response.data);
//                this.wuyous = response.data
//            })
//
//            axios.get('http://otc.xyz/api/cex',formData,{
//                headers: {'X-Requested-With': 'XMLHttpRequest','Access-Control-Allow-Origin':'*'},
//            }).then(response=>{
//                console.log(response.data.depth);
//                this.cexs = response.data.depth
//            })


        },
        data(){
            return {
                otcs : [],
                cexs : [],
                wuyous:[]
            }
        }
    }
</script>
