<template>
    <div style="">
        <select name="attrset_id" id="attrset_id" v-model="setId" @change="fnChange"  class="form-control">
            <option value="">请选择</option>
            <option v-for="set in attrsetsObj" :key="set.id" :value="set.id">{{set.name}}</option>
        </select>
    </div>
</template>
<script>
    //import {changeSet} from '../vuex/actions';
    import { mapGetters, mapActions } from 'vuex'
    export default {
        props:['attrsets','attrset-id'],
        data(){
           return {
               setId:''
           }
        },
        computed:{
            attrsetsObj:function () {
                return JSON.parse(this.attrsets);
            }
        },

        mounted(){
            if(!!this.attrsetId){
                this.setId = this.attrsetId;
                this.$store.dispatch('changeSet',this.attrsetId);
            }
        },
        
        methods:{
            fnChange(){
                this.$store.dispatch('changeSet',event.target.value);
            }
        },
    }
</script>