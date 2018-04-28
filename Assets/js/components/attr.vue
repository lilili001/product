<template>
    <div style="">
            <div v-for="(attr,key1) in saleAttrs" :key="key1" class="mar-t14">
                <label>{{attr.name}}</label>
                    <select v-model="ruleForm[attr.key]" class="form-control w200" @change="fnChange">
                        <option v-for="(option,key) in attr.options" :value="key">{{option[locale]}}</option>
                    </select>
            </div>
           
            <input type="hidden" name="saleAttrData" v-model="JSON.stringify(ruleForm)">
    </div>
</template>
<script>
    export default {
        props:['attrsets','locale','product','fillsale'],
        computed:{
           attrsetsObj:function () {
               return JSON.parse(this.attrsets)
           },
           saleAttrs:function(){
               return this.$store.state.moduleProduct.saleAttrs;
           },
           productObj:function () {
                return !!this.product ? JSON.parse(this.product) : null;
           },
           fillSaleObj:function(){
               return !!this.fillsale ? JSON.parse(this.fillsale) : null;
           }
        },
        data() {
            return {
                ruleForm: {
                },
                rules: {
                }
            };
        },
        mounted(){
            if(!this.product) return;
            var _this = this;
            var timer = setTimeout(()=>{
                this.fillSaleObj.map((item)=>{
                    let val = item.value.replace(/^"+/,"").replace(/"+$/,"");
                    _this.ruleForm[item['attr_key']] = val;
                    clearInterval(timer);
                },100);
            })
        },
        methods: {
            fnChange(){
                $('[name="saleAttrData"]').val(JSON.stringify(this.ruleForm));
            }
        }
    }
</script>