<template>
    <div class="mar-t14" v-show="skuAttrs.length > 0">
        <hr>
        <div class="info color999">价格设置需与系统默认货币一致</div>
        <el-row class="mar-t20">
            <el-col :span="2">sku规格</el-col>
            <el-col :span="22">
                <div class="sku-wrap">
                    <div v-for="(item,key ) in skuAttrs">
                        <span>{{item.name}}:</span>
                        <input type="hidden" :name="item['key']" v-model="checkList[item['key']]" required>
                        <el-checkbox-group v-model="checkList[item['key']]" @change="labelChange(item['key'])">
                            <el-checkbox :label="key1" :key="key1" v-for="(option,key1) in item.options">{{option[locale] }}
                                <el-button
                                        class="custom_button_nopadding"
                                        size="mini"
                                        plain
                                        @click.stop="fnUploadSwatch(key1)"
                                        v-if="!!checkList['color'] && checkList['color'].indexOf(key1)!=-1 &&  !!swatchColor[key1]">
                                    <img
                                            width="30"
                                            height="30"
                                            :src="!!swatchColor[key1] &&!!swatchColor[key1]['filepath'] ? swatchColor[key1]['filepath'] : '' "
                                            alt=""
                                            class="swatch">
                                </el-button>

                                <el-button
                                        @click.stop="fnUploadSwatch(key1)"
                                        v-show="item['key']=='color' &&  indexofArr(checkList[item['key']],key1) !== -1 &&    !swatchColor[key1]"
                                        size="mini">上传<i class="el-icon-upload el-icon--right"></i></el-button>
                            </el-checkbox>
                        </el-checkbox-group>
                    </div>

                    <div id="createTable" v-show="tableData6.length>0"
                         class="el-table el-table--fit el-table--border el-table--enable-row-hover el-table--enable-row-transition"></div>

                </div>
            </el-col>
        </el-row>

        <el-row class="mar-t20 mar-b14" v-show="show_size_obj">

            <el-col :span="2">size chart</el-col>
            <el-col :span="22">
                <div class="grid-content">

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="40">\</th>
                            <th v-for="(size_header,index0) in size_headers" :key="index0">{{size_header['label']}}(cm)</th>
                        </tr>

                        </thead>
                        <tbody>
                        <tr v-for="(size_row,index) in size_body" :key="index">
                            <td width="40"><span class="key w40">{{index}}</span></td>
                            <td v-for="(weidu,index2) in size_row" :key="index2">
                                <input @keyup="changeSizeDimension" type="text" :name="index+'_'+index2" v-model="size_body[index][index2]" required number min="0" >
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <input v-model="JSON.stringify(size_body)" name="size_obj" type="hidden">
                </div>
            </el-col>
        </el-row>

        <el-row class="mar-t20 mar-b14">
            <el-col :span="2">price <span class="red">(USD)</span> </el-col>
            <el-col :span="22">
                <div class="grid-content">
                    <el-input v-model="price" class=" " size="small" clearable></el-input>
                    <input type="hidden" required number="true" name="price" v-model="price"/>
                </div>
            </el-col>
        </el-row>

        <el-row class="mar-t20 mar-b14">
            <el-col :span="2">特价 <span class="red">(USD)</span> </el-col>
            <el-col :span="22">
                <div class="grid-content">
                    <el-input v-model="special_price" class="" size="small" clearable></el-input>
                    <input type="hidden" required number="true" name="special_price" v-model="special_price"/>
                </div>
            </el-col>
        </el-row>

        <el-row class="mar-t20 mar-b14">
            <el-col :span="2">特价时间</el-col>
            <el-col :span="22">
                <div class="grid-content">
                    <el-date-picker
                            v-model="date_special_price"
                            type="daterange"
                            format="yyyy-MM-dd"
                            value-format="yyyy-MM-dd"
                            range-separator="至"
                            start-placeholder="开始日期"
                            end-placeholder="结束日期">
                    </el-date-picker>
                    <input v-model="date_special_price" name="date_special_price" type="hidden" >
                </div>
            </el-col>
        </el-row>

        <el-row>
            <el-col :span="2">stock</el-col>
            <el-col :span="22">
                <div class="grid-content">
                    <el-input class=" " v-model="stock" size="small" clearable></el-input>
                    <input type="hidden" required number="true" name="stock" v-model="stock"/>
                </div>
            </el-col>
        </el-row>

        <el-dialog
                title="选择图片"
                :visible.sync="dialogVisible"
                width="800"
        >
            <ul>
                <li v-for="item in pageData.collection">
                    <div :fid="item.id" class="filediv" @click="fnSelectSwatch(item)">
                        <img :src="item.small_thumb" alt="">
                    </div>
                </li>
            </ul>
            <div class="clearfix mar-t20"></div>

            <el-pagination
                    :current-page="1"
                    :page-sizes="[15, 35, 45, 60]"
                    :page-size="100"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="pageData.total">
            </el-pagination>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
            </span>
        </el-dialog>

        <input v-model="JSON.stringify(swatchColor)" name="swatch_color" type="hidden"/>
        <input v-model="JSON.stringify(tableData6)" name="sku_data[tableData6]" type="hidden"/>
        <input v-model="JSON.stringify(checkList)" name="sku_data[checkList]" type="hidden"/>

        <!--<input v-model="price" name="sku_data[price]" type="hidden"/>
        <input v-model="special_price" name="sku_data[special_price]" type="hidden"/>
        <input v-model="stock" name="sku_data[stock]" type="hidden"/>-->

    </div>
</template>
<style scoped>
    ul {
        padding-left: 0
    }
    ul li {
        float: left;
        list-style: none;
        width: 50px;
        height: 40px;
        overflow: hidden;
        margin-right: 10px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }
    .clearfix {
        clear: both;
        text-align: center;
    }
    .color999{
        color:#999;
    }
    .sku-wrap{
        padding:20px;
        background:#f9f8f7;
        border:1px solid #ddd;
        width:800px;
    }
</style>
<style>
    .el-pagination {
        margin-top: 20px;
        text-align: center
    }
    .custom_button_nopadding {
        padding: 0;
    }
    .sku_input{border:1px solid #ddd}
    .red{color:red;font-size:12px;}
</style>
<script type="text/javascript">
    import {mapState, mapGetters, mapActions} from 'vuex';

    export default {
        props: ['pdc', 'filled-attr', 'filled-sku', 'locale'],
        name: 'sku',
        data(){
            return {
                dialogVisible: false,
                diaMsg: '',
                tableData6: [],
                result: [],
                show_size_obj:false,

                size_headers:[],
                size_body:{},

                size_obj:'',
                price: '',
                special_price:'',
                date_special_price:'',
                isFeatured: false,
                stock: '',
                curKey: 0,
                pageData: {
                    page: 1,
                    per_page: 15,
                    total: 0,
                    collection: []
                }
            }
        },
        computed: {
            skuAttrs: function () {
                return this.$store.state.moduleProduct.skuAttrs;
            },
            checkList: function () {
                let checkList = {};
                if (!this.pdc) {
                    _.each(this.skuAttrs, (item, index) => {
                        if (item) {
                            checkList[item['key']] = [];
                        }
                    });
                } else {
                    this.fillDataAttr.map((item) => {
                        let val = item.value.replace(/^"+/, "").replace(/"+$/, "");
                        checkList[item['attr_key']] = JSON.parse(val);
                    });
                }
                return checkList;
            },
            atrKeys: function () {
                let atrKeys = [];
                this.orgAtrKeys = [];
                _.each(this.skuAttrs, (item, index) => {
                    if (item) {
                        atrKeys.push({
                            key: item.key,
                            name: item.name
                        });
                    }
                });
                atrKeys = [...atrKeys, {key: 'price', name: '价格'}, {key: 'stock', name: '库存'},];
                return atrKeys;
            },
            pdcObj: function () {
                return !!this.pdc ? JSON.parse(this.pdc) : null;
            },
            swatchColor: function () {
                return !!this.pdc &&  !!this.pdcObj.swatch_colors ? JSON.parse(this.pdcObj.swatch_colors) :  {}
            },
            fillDataAttr: function () {
                return !!this.filledAttr ? JSON.parse(this.filledAttr) : null;
            },
            fillDataSku: function () {
                return !!this.filledSku ? JSON.parse(this.filledSku) : null;
            },
        },
        mounted(){
            this.$message('货币均以系统设置【默认货币】为主,默认为美元');
            this.getAllImages();
            if (!this.pdc) return;

            if((this.checkList.size).length > 0){
                this.show_size_obj = true;
                this.sizeObj = this.pdcObj.size_obj;
            }

            var timer = setTimeout(() => {
                this.tableData6 = this.fillDataSku.map((item) => {
                    return item.settings;
                });
                //反填price and stock
                this.price = this.pdcObj.price;
                this.stock = this.pdcObj.stock;
                this.special_price = this.pdcObj.special_price;
                this.size_obj = this.pdcObj.size_obj;
                this.date_special_price =  !!this.pdcObj.date_special_price ? this.pdcObj.date_special_price.split(',') : '';

                this.sizeChart();

                this.handleResult();
                if (!!this.result && this.tableData6.length > 0) {
                    this.createTableAndMerge();
                }

                clearInterval(timer);
            }, 2000);
        },
        methods: {

            changeSizeDimension(){
                this.size_obj = JSON.stringify(this.size_body);
                $('[name="size_obj"]').val(this.size_obj);
            },

            getAllImages(){
                axios.get(route('api.media.all-vue', {page: 1, per_page: 15, folder_id: 0})).then((res) => {
                    this.pageData.collection = res.data.data;
                    this.pageData.total = res.data.meta.total;
                });
            },
            fnUploadSwatch(color){
                this.dialogVisible = true;
                this.curKey = color;
            },
            fnSelectSwatch(file){
                this.swatchColor[this.curKey] = {
                    fileId: file.id,
                    filepath: file.small_thumb
                };
                this.dialogVisible = false;
                this.curKey = ''
            },
            indexofArr(arr, v){
                for (var i = 0; i < arr.length; i++) {
                    if (arr[i] == v) {
                        return i  //存在
                    }
                }
                return -1  //不存在
            },
            handleResult(){
                var arr1 = _.values(this.checkList);
                _.remove(arr1, (arr) => arr.length == 0);
                this.result = doExchange(arr1);
                return arr1;
            },
            createTableAndMerge(){
                this.createTable();
                const table = $('table');
                ( _.values(this.checkList) ).map((arr, i) => this.mergeCell(table, i));
            },
            labelChange(key){
                var _this = this;
                //this.tableData6 = [];
                const arr1 = this.handleResult();

                if( (this.checkList.size).length > 0 ){

                    this.show_size_obj = true;
                    this.sizeChart();
                }else{
                    this.show_size_obj = false;
                    this.size_obj = '';
                }

                //_this.show_size_obj = false;
                if (this.checkEveryAttrSelected(arr1)) {
                    this.tableData6 = this.result.map(str => {
                            const item = {};
                            const newArr = str.split(',');//m,l
                            this.atrKeys.map((itemk, i) => {
                                item[itemk.key] = !!newArr[i] ? newArr[i] : '';
                                return item;
                            });
                            item['price'] = '';
                            item['stock'] = '';
                            return item;
                        }
                    );
                    this.createTable();
                } else {
                    this.tableData6 = [];
                }
                const table = $('table');
                arr1.map((arr, i) => this.mergeCell(table, i));
            },
            checkEveryAttrSelected(arr1){
                return arr1.length == (_.values(this.checkList)).length;
            },
            getAttrLabel(attrkey){
                console.log(attrKey)
                // let attr = _.filter(this.skuAttrs,(attr)=>{
                //             return !!attr.options[newArr[j]]  ;
                //         })
                //         let tdVal = attr[0].options[newArr[j]][this.locale];
                //         return tdVal;
            },

            sizeChart(){
                /*尺码表自定义开始*/
                this.size_attr = _.filter( this.skuAttrs,function(item){
                    return item.key == 'size';
                });
                this.size_headers = _.groupBy( JSON.parse( (this.size_attr)[0].size_headers ) , 'locale' )[this.locale];

                this.size_options = (this.size_attr)[0].options;

                if(this.size_obj){
                    this.size_body = JSON.parse(this.size_obj);
                }else{
                    for( var size_k in this.size_options  ){
                        this.size_body[size_k] = {};
                        for(var i = 0; i< this.size_headers.length ; i++){
                            var column = this.size_headers[i];
                            this.size_body[size_k][column.value] = ''
                        }
                    }
                }

                /*尺码表自定义结束*/
            },
            createTable(){
                if ($('#createTable').children().length == 0) {
                    $('<table id="process" width="100%" border="0" cellpadding="0" cellspacing="0"><thead><tr></tr></thead><tbody></tbody></table>').appendTo($('#createTable'));
                }
                var str = '';
                this.atrKeys.map((item, i) => {
                    str += `<th><div class="cell">${item.name}</div></th>`;
                    return str;
                });

                $('#createTable thead tr').html(str);
                var strBody = '';

                var tmpAtrKeys = this.atrKeys.concat([]);
                var readyKeys = _.pullAllWith(tmpAtrKeys, [{key: 'price', name: '价格'}, {
                    key: 'stock',
                    name: '库存'
                }], _.isEqual);

                readyKeys = _.map(readyKeys, 'key');

                for (var i = 0; i < this.result.length; i++) {
                    var newArr = this.result[i].split(',');
                    var str2 = '';
                    for (var j = 0; j < newArr.length; j++) {
                        let attr = _.filter(this.skuAttrs, (attr) => {
                            return !!attr.options[newArr[j]];
                        });
                        let tdVal = attr[0].options[newArr[j]][this.locale];
                        str2 += `<td><div class="cell">${tdVal}</div></td>`;
                    }

                    var obj1 = _.zipObject(readyKeys, newArr);
                    var curRow = _.find(this.tableData6,obj1);

                    str2 += `<td><div class="cell"><input type="text" data-resi="${this.result[i]}" class="ivu-input sku_input price" required number="true" name="sku_row_price_${i}" name1="sku_row_price[]" value="${curRow.price}" /><span class="red">(USD)</span></div></td>
                        <td><div class="cell"><input type="text" data-resi="${this.result[i]}" class="ivu-input sku_input" required number="true" name="sku_row_qty_${i}" name1="sku_row_qty[]" value="${curRow.stock}" /></div></td>`;
                    strBody += '<tr>' + str2 + '</tr>';
                }
                $('#createTable tbody').html(strBody);
                var _this = this;

                $('.sku_input').on('keyup', function () {
                    var tData = $(this).data('resi').split(',');
                    var newObj = _.zipObject(readyKeys, tData);
                    var index = _.findIndex(_this.tableData6, newObj);

                    //var index = $(this).parents('tr').index();
                    switch ($(this).attr('name1')) {
                        case 'sku_row_price[]':
                            _this.tableData6[index].price = this.value;
                            _this.getBasePrice();
                            break;
                        case 'sku_row_qty[]':
                            _this.tableData6[index].stock = this.value;
                            _this.getTotalStock();
                            break;
                    }
                });
                //$('.sku_input').trigger('keyup');
            },
            getBasePrice(){
                this.tableData6.sort(function (n1, n2) {
                    return n1.price - n2.price;
                });
                this.price = this.tableData6[0].price
            },
            getTotalStock(){
                let stock = 0;
                this.tableData6.map((item, index) => {
                    stock += Number(item.stock)
                });
                this.stock = stock;
                return stock;
            },
            mergeCell($table, colIndex){
                $table.data('col-content', ''); // 存放单元格内容
                $table.data('col-rowspan', 1); // 存放计算的rowspan值 默认为1
                $table.data('col-td', $()); // 存放发现的第一个与前一行比较结果不同td(jQuery封装过的), 默认一个"空"的jquery对象

                $table.data('trNum', $('tbody tr', $table).length); // 要处理表格的总行数, 用于最后一行做特殊处理时进行判断之用
                // 我们对每一行数据进行"扫面"处理 关键是定位col-td, 和其对应的rowspan
                $('tbody tr', $table).each(function (index) {

                    // td:eq中的colIndex即列索引
                    var $td = $('td:eq(' + colIndex + ')', this);

                    // 取出单元格的当前内容
                    var currentContent = $td.html();

                    // 第一次时走此分支
                    if ($table.data('col-content') == '') {
                        $table.data('col-content', currentContent);
                        $table.data('col-td', $td);
                    } else {
                        // 上一行与当前行内容相同
                        if ($table.data('col-content') == currentContent) {
                            // 上一行与当前行内容相同则col-rowspan累加, 保存新值
                            var rowspan = $table.data('col-rowspan') + 1;
                            $table.data('col-rowspan', rowspan);
                            // 值得注意的是 如果用了$td.remove()就会对其他列的处理造成影响
                            $td.hide();
                            // 最后一行的情况比较特殊一点
                            // 比如最后2行 td中的内容是一样的, 那么到最后一行就应该把此时的col-td里保存的td设置rowspan
                            if (++index == $table.data('trNum'))
                                $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                        } else { // 上一行与当前行内容不同
                            // col-rowspan默认为1, 如果统计出的col-rowspan没有变化, 不处理
                            if ($table.data('col-rowspan') != 1) {
                                $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                            }
                            // 保存第一次出现不同内容的td, 和其内容, 重置col-rowspan
                            $table.data('col-td', $td);
                            $table.data('col-content', $td.html());
                            $table.data('col-rowspan', 1);
                        }
                    }
                });
            }
        }//end method
    }

    /**检查 某个元素v 在数组中是否存在,存在返回1  不存在返回-1**/
    function indexofArr(arr, v) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] == v) {
                return i  //存在
            }
        }
        return -1  //不存在
    }

    function doExchange(doubleArrays) {
        var len = doubleArrays.length;
        if (len >= 2) {
            var arr1 = doubleArrays[0];
            var arr2 = doubleArrays[1];
            var len1 = doubleArrays[0].length;
            var len2 = doubleArrays[1].length;
            var newlen = len1 * len2;
            var temp = new Array(newlen);
            var index = 0;

            for (var i = 0; i < len1; i++) {
                for (var j = 0; j < len2; j++) {
                    temp[index] = arr1[i] + "," + arr2[j];
                    index++;
                }
            }
            var newArray = new Array(len - 1);
            newArray[0] = temp;

            if (len > 2) {
                var _count = 1;
                for (var i = 2; i < len; i++) {
                    newArray[_count] = doubleArrays[i];
                    _count++;
                }
            }
            return doExchange(newArray);
        }
        else {
            return doubleArrays[0];
        }
    }

</script>