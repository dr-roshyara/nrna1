<template>
    <app-layout> 
         <!-- here is the table 
          -->       

            <div class="mx-auto flex flex-col w-full   md: p-4 text-center">
                <div class=" p-2 mb-2 text-gray-900 font-bold text-lg">
                    Notices issued from Election commisssion.| 
                    निर्वाचन कमिटीले प्रकाशित गरेका सुचनाहरु 
                </div>
                <table class="w-full text-left rounded p-4">
                    <tr class="border-b-2 border-gray-400 bg-blue-100"> 
                         <th class="hidden md:block p-1 md:p-3">S.N.(क्रम संख्या)</th>
                        <!-- <th class="w-1/3 p-4">Notice Title (सुचना शिर्षक) </th> -->
                        <th class="p-1 md:p-3">Notice Title (सुचना  शिर्षक) </th>
                         <!-- <th class="w-1/3 p-4">पदहरु </th> -->
                         <th class="hidden md:block p-1 md:p-3 text-center"> Date of Notice (प्रकाशित मिती ) </th> 
                          <th class="p-1 md:p-3"> Download</th>                        
                    </tr> 
                    <tr
                        v-for="(notice, noticeIndx) in notices" 
                        :key="noticeIndx" 
                        :class="{
                            'bg-gray-100 border-b border-gray-100':
                                noticeIndx % 2 === 0,
                            'bg-white border-b border-gray-100':
                                noticeIndx % 2 > 0,
                        }"
                    >
                       <td class="hidden md:block p-1 md:p-3">
                            {{ noticeIndx+1 }} 
                        </td> 
                         
                         <!-- <td class="w-1/3 p-4">
                            {{ notice.title }} 
                        </td> -->
                        <td class=" p-1 md:p-3"> 
                            <!-- {{notice.pdf_path}}   -->
                           <a :href="notice.url_path" > {{notice.title}}</a>   
                        </td> 

                        <!-- <td class="w-1/3 p-4">{{ notice.nepali_name}}</td> -->
                        <td class="hidden md:block p-1 md:p-3 text-center">{{ notice.issued_at }}</td>
                         <td class="block p-1 md:p-3"> 
                            <!-- {{notice.pdf_path}}   --> 
                           <a :href="notice.url_path" > 
                               <span class=" p-1 md:p-2 bg-red-50 font-bold text-sm"> click here to download </span> 
                            </a>   
                        </td> 
                    </tr>
                </table>
            </div>

        <!-- ends of table  -->
    </app-layout> 
    <!-- <div> <footer> </footer></div>  -->
</template>
<script>
import AppLayout from "@/Layouts/NrnaLayout";  
// import AppLayout from "@/Jetstream/Header";
// import Footer from "@/Jetstream/Footer";
export default {
    props: { 
        notices: Object,
    },
    methods:{
       
    },
  
    components: {
        // NrnaLayout,
        AppLayout
        
    },
};
</script>
