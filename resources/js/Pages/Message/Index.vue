<template >
    <nrna-layout>
    <div class="min-h-screen ">
    <div class="flex  flex-col justify-center m-auto space-y-3 px-4">
            <!-- <div class="w-1/2">
                    <message> </message>
                  Message sent to

            </div>  -->
            <!-- <div class=""> -->
                    <div class="flex flex-row justify-center">
                    <!--  Here comes the filtering  -->
                    <div class="p-2 m-1 flex flex-col" >

                        <label for="search" > Search</label>
                        <input  id="search" type="text" v-model="term" @keyup ="searching"
                        class="ml-2 px-2 py-1 text-sm bg-blue-300 rounded border">

                    </div>
                    <div class="p-2 m-1 flex flex-col" >

                        <label for="ssearch" > S Search</label>
                        <input  id="ssearch" type="text" v-model="params.search"
                        class="ml-2 px-2 py-1 text-sm bg-blue-300 rounded border">

                    </div>
                    </div>
                     <table class="mr-2 px-2">
                      <tr class="bg-blue-600 p-2 text-white">
                        <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('id')">Nr
                         <svg  v-if="params.field==='id' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='id' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>
                         </span></th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-bold"><span  class=" flex flex-row space-x-2" @click="sort('from')">From
                        <svg  v-if="params.field==='from' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='from' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>
                        </span></th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('to')"> To
                         <svg  v-if="params.field==='to' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='to' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>

                        </span></th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('created_at')">Created at
                        <svg  v-if="params.field==='created_at' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='created_at' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>

                        </span> </th>
                        <!-- here start -->
                         <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('message_sender_name')">Sender's Name
                        <svg  v-if="params.field==='message_sender_name' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='message_sender_name' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>

                        </span> </th>

                        <!-- here ends -->
                       <!-- here start -->
                         <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('message_receiver_name')">Receiver's Name
                        <svg  v-if="params.field==='message_receiver_name' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#626262"/></svg>
                         <svg v-if="params.field==='message_receiver_name' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#626262"/></svg>

                        </span> </th>
                        <!-- here ends -->
                        <!-- here ends the header -->
                      </tr>
                      <tr v-for ="(message, index) in messages.data" :key='index' :class="{'bg-gray-200 ':index%2===0}">
                        <th class="px-2 py-2 mb-1 text-left text-sm font-semibold">{{message.id}} </th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-semibold"> {{message.from}} </th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-semibold"> {{message.to}} </th>
                        <th class="px-2 py-2 mb-1 text-left text-sm font-semibold"> {{message.created_at}} </th>
                         <th class="px-2 py-2 mb-1 text-left text-sm font-semibold"> {{message.message_sender_name}} </th>
                         <th class="px-2 py-2 mb-1 text-left text-sm font-semibold"> {{message.message_receiver_name}}  </th>


                        <!-- here ends the row  -->
                       </tr>
                    </table>
                <!-- </div> -->

          </div>
            <div class="p-5 flex justify-end">
            <inertia-link  v-if="messages.prev_page_url" class="px-2" :href="messages.prev_page_url" >Previous Page </inertia-link>
            <inertia-link  v-if="messages.next_page_url" class="px-2" :href="messages.next_page_url" >Next Page </inertia-link>

            </div>
             <pagination class="mt-10" :links="messages.links" />
    </div>
    </nrna-layout>
</template>
<script>
// import Message from "@/Pages/Message/Message.vue";
import NrnaLayout from '@/Layouts/NrnaLayout'
import { Inertia } from '@inertiajs/inertia';
import _ from 'lodash';

export default {
    props:{
        messages: Object,
        filters: Object
        // from: String,
        // to: String,

    },
    data(){
        return {
            term: '',
            params: {
                search:     this.filters.search,
                field:      this.filters.field,
                direction:  this.filters.direction
            }
        }
    },
    watch:{
        params: {
            handler (){
                let params =this.params;
                Object.keys(params).forEach(key =>{
                    if(params[key] ==''){ delete params[key]}
                });
               // the above thing can be done by using the following one line
                //let params =pickBy(this.params);
                this.$inertia.get(route('messages.index'),
                    params,
                    { replace:true,  preserveState: true });
                },
            deep:true
        }
    },
    methods:{
        // search(){
        //     this.$inertia.replace(this.$route('messages.index',{term: this.term}));
        // }
        searching: _.throttle(function(){
            this.$inertia.replace(route('messages.index',{term: this.term}))
        }, 200),
        sort(field){
            this.params.field     =field;
            console.log(this.params.direction);
            if(this.params.direction ==='desc'){
                this.params.direction = 'asc'
            }else{
                this.params.direction = 'desc'
            }
            //this.params.direction = this.params.direction === 'asc'  ?  'desc' :  'asc';
        }
    },
    components: {
    //  Message
    NrnaLayout
    }
}
</script>
