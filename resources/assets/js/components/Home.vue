<template>
    <div class="container">
        <div class="row trade_content">
            <div data-v-b07140c2="" class="left">
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

                        <div v-show="loadding" class="loading"></div>

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
                                    <div data-v-45e29ae5="" class="buy"><a :href="otc.url">购买</a></div>
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
            <div data-v-b07140c2="" class="right">
                <div data-v-09c9f5fa="" data-v-b07140c2="" class="trade_sell">
                    <div data-v-09c9f5fa="" class="trade_sell_header">
                        买方发布
                    </div>
                    <div data-v-09c9f5fa="" class="trade_sell_title">
                        <div data-v-09c9f5fa="" class="el-row">
                            <div data-v-09c9f5fa="" class="trade_sell_title_item user el-col el-col-4">用户名</div>
                            <div data-v-09c9f5fa="" class="trade_sell_title_item limit el-col el-col-10">限额</div>
                            <div data-v-09c9f5fa="" class="trade_sell_title_item price el-col el-col-6">价格</div>
                        </div>
                    </div>

                    <div v-show="loadding" class="loading"></div>
                    <div data-v-45e29ae5="" class="trade_list" v-for="otcsell in otcsells" :key="otcsell.id">
                        <div data-v-45e29ae5="" class="trade_buy_item el-row">
                            <div data-v-45e29ae5="" class="trade_buy_col user el-col el-col-5">
                                {{otcsell.name}}
                            </div>
                            <div data-v-45e29ae5="" class="trade_buy_col limit el-col el-col-3">
                                {{otcsell.min_qty}}-{{otcsell.max_qty}} OSC
                            </div>
                            <div data-v-45e29ae5="" class="trade_buy_col price el-col el-col-3">
                                {{otcsell.price}} CNY
                            </div>
                            <div data-v-45e29ae5="" class="trade_buy_col button_group el-col el-col-4">
                                <div data-v-45e29ae5="" class="buy"><a :href="otcsell.url">出售</a></div>
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
        mounted(){
          this.getotc();
        },
        methods: {
            getotc(){
                this.loadding = true;
                axios.get('http://otc.xyz/api/otc?s='+Math.random(),{
                    headers: {'X-Requested-With': 'XMLHttpRequest','Access-Control-Allow-Origin':'*'},
                }).then(response=>{
                    //console.log(response.data);
                    this.loadding = false;
                    this.otcs = response.data.otc;
                    this.otcsells = response.data.otcsell;
                })
            }
        },
        data(){
            return {
                loadding: false,
                otcs : [],
                otcsells : [],
            }
        },
        created(){
            this.intervalid1 = setInterval(() => {
                this.getotc();
            }, 30000)
        }
    }
</script>
