<template>
<nrna-layout>
<app-layout>
      <div class="mt-6 text-center"> 
        <jet-validation-errors class="mb-4  mx-auto text-center " />  
         <div class="m-auto text-center bg-blue-200 py-4 ">  
        <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
          <p> You have given the correct voting code. you can Vote now!</p>
        <p class="m-auto"> Please select the correct candidates of your choice</p>
        <p> यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ। </p>
      </div>  
      </div>
      <form @submit.prevent="submit" class=" text-center mx-auto mt-10">
        <div class="flex flex-col justify-center px-2 m-2"> 
           <!-- Two divs 
            **First : Voting option 
            **Second no vote option            
            -->
            
              <div v-if ="! this.no_vote_option" class="flex flex-col">  <!-- Here starts the voting option  -->

                  <!-- ****************ICC Member ******************************************************************************************************** -->       
                    <div id="first_vote_window" 
                     class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please choose one  Candidate as the ICC Member. </label> 
                      <label class="p-2"> कृपया एक जना लाई आइसीसी सदस्य  चुन्नुहोस ।  </label>   
                    </div>
                    <!-- here starts: icc -->
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(icc_member, pIndx) in icc_members" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="icc_member.post_name"   
                            :post_nepali_name   ="icc_member.post_nepali_name"  
                            :candidacy_name     ="icc_member.candidacy_name">
                            </show-candidate>              
                                      <!-- here starts -->
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="icc_member.user_id"
                            :name     ="icc_member.post_name"
                            :value    ="icc_member.candidacy_id"  
                            v-model   ="form.icc_member"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 
                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change    ="updateBoxes(this.icc_members,this.form.icc_member,this.icc_memberTicks)" 
                            :disabled ="icc_member.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <div  class="mb-4 p-2" v-if="form.icc_member.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.icc_members,this.form.icc_member)}} </span> as President of NRNA Germany!</div>
                    </div>
                    <!-- next -->
                  <!-- ****************President ******************************************************************************************* -->       
                    <!-- label -->
                  <div  id="second_vote_window"
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">        
                    <div class="flex flex-col text-xl text-gray-900 font-bold">
                      <label> Please choose one  Candidate as the president. </label> 
                      <label class="p-2"> कृपया एक जना लाई अद्यक्ष चुन्नुहोस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(president, pIndx) in presidents" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="president.post_name"   
                            :post_nepali_name   ="president.post_nepali_name"  
                            :candidacy_name     ="president.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx -->
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="president.user_id"
                            :name     ="president.post_name"
                            :value    ="president.candidacy_id"  
                            v-model   ="form.president"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.presidents,this.form.president,this.presidentTicks)" 
                            :disabled ="president.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.president.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.presidents,this.form.president)}} </span> as President of NRNA Germany!</div>
                    </div>
                  <!--end of  president -->
                                  <!-- next -->
                  <!-- ****************Vice president **************************************************************************** -->  
                  <div id="first_vote_window" 
                    class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">        
                   <div class="flex flex-col text-xl text-gray-900 font-bold">
                      <label> Please choose one  Candidate as the president. </label> 
                      <label class="p-2"> कृपया दुई जना लाई उपाद्यक्ष  चुन्नुहोस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(vice_president, pIndx) in vice_presidents" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="vice_president.post_name"   
                            :post_nepali_name   ="vice_president.post_nepali_name"  
                            :candidacy_name     ="vice_president.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx -->
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="vice_president.user_id"
                            :name     ="vice_president.post_name"
                            :value    ="vice_president.candidacy_id"  
                            v-model   ="form.vice_president"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.vice_presidents, this.form.vice_president,this.vice_presidentTicks)" 
                            :disabled ="vice_president.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.vice_president.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.vice_presidents,this.form.vice_president)}} </span> 
                      as Vice President of NRNA Germany!</div>
                    </div>
                  <!--end of  Vice president -->
                                          <!-- next -->
                  <!-- ****************woman vice president **************************************************************************** -->  
                  <div  id="second_vote_window" 
                  v-if ="this.wvps.length>0" 
                    class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">        
                   <div class="flex flex-col text-xl text-gray-900 font-bold">
                      <label> Please choose one  Candidate as the  Woman Vice President. </label> 
                      <label class="p-2"> कृपया एक जना लाई  महिला उपाद्यक्ष  चुन्नुहोस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(wvp, pIndx) in wvps" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="wvp.post_name"   
                            :post_nepali_name   ="wvp.post_nepali_name"  
                            :candidacy_name     ="wvp.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="wvp.user_id"
                            :name     ="wvp.post_name"
                            :value    ="wvp.candidacy_id"  
                            v-model   ="form.wvp"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.wvps, this.form.wvp,this.wvpTicks)" 
                            :disabled ="wvp.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.wvp.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.wvps,this.form.wvp)}} </span> 
                      as Woman Vice President of NRNA Germany!</div>
                    </div>
                  <!--end of  woman sVice president -->
                            <!-- next -->
                  <!-- ****************General Secretary  **************************************************************************** -->  
                  <div id="first_vote_window"  v-if="this. general_secretarys.length>0" 
                  class="flex flex-col border border-3 border-blue-300 m-2 py-4 px-6">        
                 <div class="flex flex-col text-xl text-gray-900 font-bold">
                      <label> Please choose one  Candidate as the president. </label> 
                      <label class="p-2"> कृपया एक जनालाई महासचिव चुन्नुहोस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(general_secretary, pIndx) in general_secretarys" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="general_secretary.post_name"   
                            :post_nepali_name   ="general_secretary.post_nepali_name"  
                            :candidacy_name     ="general_secretary.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx -->
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="general_secretary.user_id"
                            :name     ="general_secretary.post_name"
                            :value    ="general_secretary.candidacy_id"  
                            v-model   ="form.general_secretary"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.general_secretarys, this.form.general_secretary,this.general_secretaryTicks)" 
                            :disabled ="general_secretary.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.general_secretary.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.general_secretarys,this.form.general_secretary)}} </span> 
                      as General secretary of NRNA Germany!</div>
                    </div>
                  <!--end of  Vice president -->

              
                                          <!-- next -->
                  <!-- **************** Secretary **************************************************************************** -->  
                  <div  id="second_vote_window" v-if ="this.secretarys.length>0" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-blue-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any two candidates</label> 
                      <label class="p-2"> कृपया दुई जना लाई सचिव छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(secretary, pIndx) in secretarys" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="secretary.post_name"   
                            :post_nepali_name   ="secretary.post_nepali_name"  
                            :candidacy_name     ="secretary.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="secretary.user_id"
                            :name     ="secretary.post_name"
                            :value    ="secretary.candidacy_id"  
                            v-model   ="form.secretary"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.secretarys, this.form.secretary,this.secretaryTicks)" 
                            :disabled ="secretary.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.secretary.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.secretarys,this.form.secretary)}} </span> 
                      as Secretary of NRNA Germany!</div>
                    </div>
                  <!--end of Secretarys  -->

                            <!-- next -->
        
                  <!-- **************** Treasure **************************************************************************** -->  
                  <div id="first_vote_window" v-if ="this.treasures.length>0" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एक  जना लाई कोषाद्यक्ष  छान्नु होस ।   </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(treasure, pIndx) in treasures" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="treasure.post_name"   
                            :post_nepali_name   ="treasure.post_nepali_name"  
                            :candidacy_name     ="treasure.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="treasure.user_id"
                            :name     ="treasure.post_name"
                            :value    ="treasure.candidacy_id"  
                            v-model   ="form.treasure"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.treasures, this.form.treasure,this.treasureTicks)" 
                            :disabled ="treasure.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.treasure.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.treasures,this.form.treasure)}} </span> 
                      as Treasure of NRNA Germany!</div>
                    </div>
                  <!--end of  Treasure president -->
                <!-- **************** Woman Coordinator **************************************************************************** -->  
                  <div  id="second_vote_window" v-if ="this.w_coordinators.length>0" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6  shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एक  जना लाई महिला कोर्डिनेटर   छान्नु होस  । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(w_coordinator, pIndx) in w_coordinators" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="w_coordinator.post_name"   
                            :post_nepali_name   ="w_coordinator.post_nepali_name"  
                            :candidacy_name     ="w_coordinator.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="w_coordinator.user_id"
                            :name     ="w_coordinator.post_name"
                            :value    ="w_coordinator.candidacy_id"  
                            v-model   ="form.w_coordinator"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.w_coordinators, this.form.w_coordinator,this.w_coordinatorTicks)" 
                            :disabled ="w_coordinator.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.w_coordinator.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.w_coordinators,this.form.w_coordinator)}} </span> 
                      as Woman coordinator of NRNA Germany!</div>
                    </div>
                  <!--end of  w_coordinator  -->
                            <!-- **************** Youth Coordinator **************************************************************************** -->  
                  <div id="first_vote_window" v-if ="this.y_coordinators.length>0" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एक  जना लाई युबा  कोर्डिनेटर   छान्नु होस ।  </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(y_coordinator, pIndx) in y_coordinators" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="y_coordinator.post_name"   
                            :post_nepali_name   ="y_coordinator.post_nepali_name"  
                            :candidacy_name     ="y_coordinator.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="y_coordinator.user_id"
                            :name     ="y_coordinator.post_name"
                            :value    ="y_coordinator.candidacy_id"  
                            v-model   ="form.y_coordinator"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.y_coordinators, this.form.y_coordinator,this.y_coordinatorTicks)" 
                            :disabled ="y_coordinator.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div id="first_vote_window"  class="mb-4 p-2" v-if="form.y_coordinator.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.y_coordinators,this.form.y_coordinator)}} </span> 
                      as Youth  coordinator of NRNA Germany!</div>
                    </div>
                  <!--end of  Y_coordinator  -->
                              <!-- **************** Culture Coordinator **************************************************************************** -->  
                  <div id="second_vote_window" 
                  v-if ="this.cult_coordinators.length>0" class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2">कृपया एक  जना लाई साँस्क्रितिक   कोर्डिनेटर   छान्नु होस ।</label>    
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(cult_coordinator, pIndx) in cult_coordinators" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="cult_coordinator.post_name"   
                            :post_nepali_name   ="cult_coordinator.post_nepali_name"  
                            :candidacy_name     ="cult_coordinator.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="cult_coordinator.user_id"
                            :name     ="cult_coordinator.post_name"
                            :value    ="cult_coordinator.candidacy_id"  
                            v-model   ="form.cult_coordinator"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.cult_coordinators, this.form.cult_coordinator,this.cult_coordinatorTicks)" 
                            :disabled ="cult_coordinator.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.cult_coordinator.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.cult_coordinators,this.form.cult_coordinator)}} </span> 
                      as Culture   coordinator of NRNA Germany!</div>
                    </div>
                  <!--end of  cult_coordinator  -->
                <!-- **************** child Coordinator **************************************************************************** -->  
                  <div id="first_vote_window"  
                  v-if ="this.child_coordinators.length>0" 
                    class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एक  जना लाई भाषा साहित्य तथा बालबालिका कोर्डिनेटर   छान्नु होस ।</label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(child_coordinator, pIndx) in child_coordinators" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="child_coordinator.post_name"   
                            :post_nepali_name   ="child_coordinator.post_nepali_name"  
                            :candidacy_name     ="child_coordinator.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="child_coordinator.user_id"
                            :name     ="child_coordinator.post_name"
                            :value    ="child_coordinator.candidacy_id"  
                            v-model   ="form.child_coordinator"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.child_coordinators, this.form.child_coordinator,this.child_coordinatorTicks)" 
                            :disabled ="child_coordinator.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.child_coordinator.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.child_coordinators,this.form.child_coordinator)}} </span> 
                      as Language Literature and Children  coordinator of NRNA Germany!</div>
                    </div>
                  <!--end of  CHILD_coordinator  -->
                <!-- **************** Student  Coordinator **************************************************************************** -->  
                  <div id="second_vote_window"
                  v-if ="this.studt_coordinators.length>0" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एक  जना लाई विद्यार्थी   कोर्डिनेटर   छान्नु होस  ।</label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(studt_coordinator, pIndx) in studt_coordinators" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="studt_coordinator.post_name"   
                            :post_nepali_name   ="studt_coordinator.post_nepali_name"  
                            :candidacy_name     ="studt_coordinator.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="studt_coordinator.user_id"
                            :name     ="studt_coordinator.post_name"
                            :value    ="studt_coordinator.candidacy_id"  
                            v-model   ="form.studt_coordinator"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.studt_coordinators, this.form.studt_coordinator,this.studt_coordinatorTicks)" 
                            :disabled ="studt_coordinator.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.studt_coordinator.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.studt_coordinators,this.form.studt_coordinator)}} </span> 
                      as Language Literature and Children  coordinator of NRNA Germany!</div>
                    </div>
                  <!--end of  STUDT_coordinator  -->
                <!-- **************** Member Berlin **************************************************************************** -->  
                  <div id="first_vote_window"
                  v-if ="this.member_berlins.length>0 & this.user_lcc=='Berlin'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी बर्लिन   बाट दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_berlin, pIndx) in member_berlins" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_berlin.post_name"   
                            :post_nepali_name   ="member_berlin.post_nepali_name"  
                            :candidacy_name     ="member_berlin.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_berlin.user_id"
                            :name     ="member_berlin.post_name"
                            :value    ="member_berlin.candidacy_id"  
                            v-model   ="form.member_berlin"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_berlins, this.form.member_berlin,this.member_berlinTicks)" 
                            :disabled ="member_berlin.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_berlin.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_berlins,this.form.member_berlin)}} </span> 
                      as Member Berlin!</div>
                    </div>
                  <!--end of  MEMBER_Berlin  -->
                    <!-- **************** Member Hamburg **************************************************************************** -->  
                  <div id="second_vote_window"
                    v-if ="this.member_hamburgs.length>0 & this.user_lcc==='Hamburg'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी ह्याम्बुर्ग  बाट दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_hamburg, pIndx) in member_hamburgs" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_hamburg.post_name"   
                            :post_nepali_name   ="member_hamburg.post_nepali_name"  
                            :candidacy_name     ="member_hamburg.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_hamburg.user_id"
                            :name     ="member_hamburg.post_name"
                            :value    ="member_hamburg.candidacy_id"  
                            v-model   ="form.member_hamburg"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_hamburgs, this.form.member_hamburg,this.member_hamburgTicks)" 
                            :disabled ="member_hamburg.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_hamburg.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_hamburgs,this.form.member_hamburg)}} </span> 
                      as Member Hamburg!</div>
                    </div>
                  <!--end of  MEMBER_hamburg  -->   

                  <!-- **************** Member Niedersachsen **************************************************************************** -->  
                  <div id="first_vote_window"
                  v-if ="this.member_nsachsens.length>0 & this.user_lcc==='Niedersachsen'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी निदरज्याक्सन   बाट  दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_nsachsen, pIndx) in member_nsachsens" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_nsachsen.post_name"   
                            :post_nepali_name   ="member_nsachsen.post_nepali_name"  
                            :candidacy_name     ="member_nsachsen.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_nsachsen.user_id"
                            :name     ="member_nsachsen.post_name"
                            :value    ="member_nsachsen.candidacy_id"  
                            v-model   ="form.member_nsachsen"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_nsachsens, this.form.member_nsachsen,this.member_nsachsenTicks)" 
                            :disabled ="member_nsachsen.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_nsachsen.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_nsachsens,this.form.member_nsachsen)}} </span> 
                      as Member Niedersachsen!</div>
                    </div>
                  <!--end of  MEMBER_nsachsen  -->  
                    <!-- NRW -->
                  <!-- **************** Member NRW **************************************************************************** -->  
                  <div id="second_vote_window"
                  v-if ="this.member_nrws.length>0 & this.user_lcc==='NRW'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया  एलसीसी एनआरड्ब्ल्यु    बाट  दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_nrw, pIndx) in member_nrws" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_nrw.post_name"   
                            :post_nepali_name   ="member_nrw.post_nepali_name"  
                            :candidacy_name     ="member_nrw.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_nrw.user_id"
                            :name     ="member_nrw.post_name"
                            :value    ="member_nrw.candidacy_id"  
                            v-model   ="form.member_nrw"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_nrws, this.form.member_nrw,this.member_nrwTicks)" 
                            :disabled ="member_nrw.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_nrw.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_nrws,this.form.member_nrw)}} </span> 
                      as Member NRW!</div>
                    </div>
                  <!--end of  MEMBER_nrw  -->  
                  <!--- hessen -->
                        <!-- **************** Member Hessen **************************************************************************** -->  
                  <div id="first_vote_window" 
                  v-if ="this.member_hessens.length>0 & this.user_lcc=='Hessen'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी एलसीसी हेस्सेन बाट दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_hessen, pIndx) in member_hessens" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_hessen.post_name"   
                            :post_nepali_name   ="member_hessen.post_nepali_name"  
                            :candidacy_name     ="member_hessen.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_hessen.user_id"
                            :name     ="member_hessen.post_name"
                            :value    ="member_hessen.candidacy_id"  
                            v-model   ="form.member_hessen"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_hessens, this.form.member_hessen,this.member_hessenTicks)" 
                            :disabled ="member_hessen.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_hessen.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_hessens,this.form.member_hessen)}} </span> 
                      as Member Hamburg!</div>
                    </div>
                  <!--end of  MEMBER_hessen  -->  
                  <!-- Rhein land pfalz -->  
                        <!-- **************** Member Rhein pfalz **************************************************************************** -->  
                  <div id="second_vote_window"
                  v-if ="this.member_rhein_pfalzs.length>0 & this.user_lcc=='Rheinland Pfalz'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी राइन ल्यान्ड फाल्ज  बाट दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_rhein_pfalz, pIndx) in member_rhein_pfalzs" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_rhein_pfalz.post_name"   
                            :post_nepali_name   ="member_rhein_pfalz.post_nepali_name"  
                            :candidacy_name     ="member_rhein_pfalz.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_rhein_pfalz.user_id"
                            :name     ="member_rhein_pfalz.post_name"
                            :value    ="member_rhein_pfalz.candidacy_id"  
                            v-model   ="form.member_rhein_pfalz"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_rhein_pfalzs, this.form.member_rhein_pfalz,this.member_rhein_pfalzTicks)" 
                            :disabled ="member_rhein_pfalz.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_rhein_pfalz.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_rhein_pfalzs,this.form.member_rhein_pfalz)}} </span> 
                      as Member Rhein Land Pfalz!</div>
                    </div>
                  <!--end of  Member_rhein_pfalz  -->  
                  <!-- Bayern -->
                        <!-- **************** Member Hamburg **************************************************************************** -->  
                  <div id="first_vote_window"
                  v-if ="this.member_bayerns.length>0 & this.user_lcc==='Bayern'" 
                  class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">   
                      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please Choose any one candidates</label> 
                      <label class="p-2"> कृपया एलसीसी बायर्न  बाट दुई जना सदस्यहरु   छान्नु होस । </label>   
                    </div>
                    <!-- candidate part --> 
                    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                        <div  v-for="(member_bayern, pIndx) in member_bayerns" :key="pIndx"  
                            class="flex flex-col justify-center p-4 mb-2 text-center  
                            border border-gray-100 rounded"> 
                            <show-candidate 
                            :post_name          ="member_bayern.post_name"   
                            :post_nepali_name   ="member_bayern.post_nepali_name"  
                            :candidacy_name     ="member_bayern.candidacy_name">
                            </show-candidate>              
                            <!-- checkobx --> 
                          <div class="px-2 py-2">
                            <input 
                            type      ="checkbox"
                            :id       ="member_bayern.user_id"
                            :name     ="member_bayern.post_name"
                            :value    ="member_bayern.candidacy_id"  
                            v-model   ="form.member_bayern"
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change  ="updateBoxes(this.member_bayerns, this.form.member_bayern,this.member_bayernTicks)" 
                            :disabled ="member_bayern.disabled"
                          />
                          </div> 

                        </div>
                        

                    </div> 
                    <!-- what you have selected --> 
                    <div  class="mb-4 p-2" v-if="form.member_bayern.length"> You have selected 
                      <span class="font-bold text-indigo-600"> {{find_selected_name(this.member_bayerns,this.form.member_bayern)}} </span> 
                      as Member Bayern!</div>
                    </div>
                  <!--end of  MEMBER_bayern  -->  
          </div>  <!-- End of Voting option   --> 
             
             <!-- ****************NO vode option ******************************************************************************************* -->       
      <!-- ****************NO vode option ******************************************************************************************* -->       
            <!-- ****************NO vode option ******************************************************************************************* -->       
         
             
              <!-- Here comes the no vote Button  -->
              <div  id="second_vote_window"
               class="flex flex-col border border-2 border-blue-300 m-2 py-4 px-6"> 
                <div class=" flex flex-col items-center justify-center py-2 mb-2 text-bold text-red-500 text-xl">
                 <p> कुनै पनि उमेद्बारहरुलाई स्विकार गर्न नचाहने हरुका लागि मात्र </p> 
                <p>  Option for Rejection</p>
                <p>
                      उमेदवारहरु लाई अस्विकार को लागि मतदान । </p> 
                </div>
               <div class="px-2 py-2">
                    <input 
                    type      ="checkbox"
                    :id       ="no_vote_option"
                    :name     ="no_vote_option"
                    :value    =true
                    v-model   ="form.no_vote_option"
                    class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    @change  ="update_no_vote_option()" 
                  />
                  </div> 
                  <p> By clicking this button, You agree that you are rejecting  all the candidates.  </p>                      
                  <p> यो भोटमा क्लिक गरेर म यो लिस्टमा उल्लेखित कुनै पनि उमेद्बारलाई  मतदानलाई नगरि आफ्नो अभिमत ""राइट टु रिजेक्ट अल क्यान्डिडेट्स" 
                    लाई सुरक्षित गर्न चाहन्छु। 
                   </p>

              </div>     
           <!--end of no vote button -->             
            <div class="flex flex-col items-center mx-auto my-4 w-full by-4 " style="background-color: #F1F1F1;"> 
              <!-- Here comes the no vote Button  -->
              <div  class="flex flex-col border border-3 border-blue-300 mx-2 my-4 py-4 px-6"> 
                <div class=" flex flex-col items-center justify-center py-2 mb-2 text-bold text-red-700 text-xl">
                      <p> Button for Agreement </p> 
                     <p> मतदान गरेको स्विकार </p>  
                </div>
               <div class="px-2 py-2">
                    <input 
                    type      ="checkbox"
                    :id       ="agree_button"
                    :name     ="agree_button"
                    :value    =true
                    v-model   ="form.agree_button"
                    class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                 
                  />
                  </div> 
                  <p>  By clicking this button, I conform that I have chosen the candidates correctly and I followed the online rules to vote the candidates. </p>
                <p>यो बटनमा थिचेर मैले माथि छाने आनुसार  मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु। </p> 
             
                   <button type="submit" class="mx-2 my-4 px-2 py-6 rounded-lg bg-blue-300 w-full mx-auto shadow-sm text-xl font-bold text-gray-900">
                   Submit
                   </button>
              

              </div>
              <!-- here ends -->
            </div>
             <!-- here comes the error  -->
            <div class="mx-auto text-center">
              <jet-validation-errors class="mb-4  mx-auto text-center " />
            </div>
        </div>
      </form>
     
  </app-layout>
</nrna-layout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'
import ShowCandidate from '@/Shared/ShowCandidate'
import JetInput from '@/Jetstream/Input'
import ShowCheckbox from "@/Shared/ShowCheckbox";
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'    
export default {
  props:{
     candidacies:Object,
     user_name : String,
     user_id : Number,  
     user_lcc : String   
  },
 
  data(){ 
      return{
        /**
        ** Write here the candidate ids:
        *  They are saved in posts table . 
        */
        icc_member_post_id:           "2021_01",
        president_post_id:            "2021_02",
        vice_president_post_id:       "2021_03",
        wvp_post_id:                   "2021_04",
        gs_post_id :                   "2021_05",
        secretary_post_id:               "2021_06",
        treasure_post_id :                "2021_07",
        w_coordinator_post_id:             "2021_08", 
        y_coordinator_post_id :             "2021_09",
        cult_coordinator_post_id:            "2021_10",
        child_coordinator_post_id:            "2021_11",
         studt_coordinator_post_id:            "2021_12",
        member_berlin_post_id :             "2021_13",
        member_hamburg_post_id:            "2021_14",
        member_nsachsen_post_id:          "2021_15",
        member_nrw_post_id :              "2021_16",
       member_hessen_post_id:             "2021_17",
       member_rhein_pfalz_post_id:       "2021_18",
       member_bayern_post_id:             "2021_19",
      //+---------------------------------+---------+  
   
        no_vote_option: 0,
        
        /** 
        * Define Ticks for each Post seperately 
        *
        */

         icc_memberTicks:{
            limit: 1,
            ticks:[]
          },
          //
          presidentTicks:{
            limit: 1,
            ticks:[]
          },
          //vice-president 
         vice_presidentTicks:{
            limit: 2,
            ticks:[]
          },
          //general secretay ticks
          wvpTicks:{
            limit: 1,
            ticks:[]
          },
        //general secretay ticks
          general_secretaryTicks:{
            limit: 1,
            ticks:[]
          },
          //next 
          secretaryTicks:{
            limit: 2,
            ticks:[]
          },
          // secretay ticks
          treasureTicks:{
            limit: 1,
            ticks:[]
          },
        //
          // secretay ticks
          w_coordinatorTicks:{
            limit: 1,
            ticks:[]
          },
        //
      // Yyouth controller 
        y_coordinatorTicks:{
            limit: 1,
            ticks:[]
          },
        //
      // culture controller 
        cult_coordinatorTicks:{
            limit: 1,
            ticks:[]
          },
        // culture controller 
        child_coordinatorcTicks:{
            limit: 1,
            ticks:[]
          },
            
        //student y_coordinator
        studt_coordinatorcTicks:{
            limit: 1,
            ticks:[]
          },
               
        //Members 
        member_berlinTicks:{
            limit: 2,
            ticks:[]
          },
        //Members 
        member_hamburgTicks:{
            limit: 2,
            ticks:[]
          },
        //        //Members 
        member_nsachsenTicks:{
            limit: 2,
            ticks:[]
          },
        //Members 
        member_nrwTicks:{
            limit: 2,
            ticks:[]
          },
         //Members 
        member_hessenTicks:{
            limit: 2,
            ticks:[]
          },
         //Members 
        member_rhein_pfalzTicks:{
            limit: 2,
            ticks:[]
          },
        //
       //Members 
        member_bayernTicks:{
            limit: 2,
            ticks:[]
          },


          //end of ticks 
          ticks: [],
       
      }
  },
  setup (props) {
    const form = useForm({
      user_id:          props.user_id,
      icc_member:             [],
      president:              [],
      vice_president:         [], 
      wvp:                    [], 
      general_secretary:      [],
      secretary:              [],
      treasure:               [],
      w_coordinator:          [],
      y_coordinator:          [],
      cult_coordinator:       [],
      child_coordinator:      [],
      studt_coordinator:      [],
      member_berlin:          [],
      member_hamburg:         [],
      member_nsachsen:        [],
      member_nrw:             [],
      member_hessen:          [],
      member_rhein_pfalz:     [],
      member_bayern:          [],     
      no_vote_option:       false,
      agree_button:          false,     
      
    })
    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
   
      form.post('/vote/submit')
    }

    return { form, submit }
  },
  computed: {
      getLength(){        
        return this.form.icc_member.length;
      },
      
      /***************************************************************************
      * We compute the candidacies here 
      *
      ******************************************************************************/
      icc_members (){
        // return pres1;
        
        return this.create_candidates(this.icc_member_post_id)
      //  return this.create_candidates(this.icc_member_post_id).sorty(this.sortByName)
     
      },
     presidents (){
        // return pres1;
         return this.create_candidates(this.president_post_id)
      },
      vice_presidents (){
        // return pres1;
         return this.create_candidates(this.vice_president_post_id)
      },
      //
          wvps (){
        // return pres1;
         return this.create_candidates(this.wvp_post_id)
      },
      //
      general_secretarys (){
        // return pres1;
         return this.create_candidates(this.gs_post_id)
      },
     // secre
      secretarys (){
        // return pres1;
         return this.create_candidates(this.secretary_post_id)
      },
      //
      treasures (){
        // return pres1;
         return this.create_candidates(this.treasure_post_id)
      },
      //
       w_coordinators(){
        // return pres1;
         return this.create_candidates(this.w_coordinator_post_id)
      },
      //
       y_coordinators(){
        // return pres1;
         return this.create_candidates(this.y_coordinator_post_id)
      },
     //
       cult_coordinators(){
        // return pres1;
         return this.create_candidates(this.cult_coordinator_post_id)
      },
      //
      child_coordinators(){
        // return pres1;
         return this.create_candidates(this.child_coordinator_post_id)
      },
      //
      studt_coordinators(){
        // return pres1;
         return this.create_candidates(this.studt_coordinator_post_id)
      },
    //
     member_berlins(){
        // return pres1;
         return this.create_candidates(this.member_berlin_post_id)
      },

 //
     member_hamburgs(){
        // return pres1;
         return this.create_candidates(this.member_hamburg_post_id)
      },

      member_nsachsens(){
        // return pres1;
         return this.create_candidates(this.member_nsachsen_post_id)
      },

 
      //
      member_nrws(){
        // return pres1;
         return this.create_candidates(this.member_nrw_post_id)
      },
      //
      member_hessens(){
        // return pres1;
         return this.create_candidates(this.member_hessen_post_id)
      },
      //
      member_rhein_pfalzs(){
        // return pres1;
         return this.create_candidates(this.member_rhein_pfalz_post_id)
      },
      //
           member_bayerns(){
        // return pres1;
         return this.create_candidates(this.member_bayern_post_id)
      },
 


      /***************************************************************************/
      get_selected_name(candiVec,selected_id){
          /**
           *@input1 : candidacy vector , 
           *@input2: selected id
           */
          return this.find_selected_name(candiVec, selected_id);
        // return selected_name
      },
       get_selected_ids(candiVec,selected_id){
          /**
           *@input1 : candidacy vector , 
           *@input2: selected id
           */
          return this.find_selected_ids(candiVec, selected_id);
        // return selected_name
      }
  },
  methods:{
     /**
      * This function changes the value of no vote option . 
      * That means if you click on no vote option. The form will disappear 
      * 
      */
       update_no_vote_option(){
          this.no_vote_option =this.form.no_vote_option;
          
          if(this.no_vote_option){
            //make the default value  for form 
            this.form.icc_member          = []
            this.form.president           = []
            this.form.vice_president      = []
            this.form.wvp                 = []
            this.form.general_secretary   = []
            this.form.secretary           = []
            this.form.treasure            = []
            this.form.w_coordinator       =[]
            this.form.y_coordinator       =[]
            this.form.cult_coordinator    =[]
            this.form.child_coordinator   =[]
            this.form.studt_coordinator    =[]
            this.form.member_berlin       =[]
            this.form.member_hamburg      =[]
            this.form.member_nsachsen     =[]
            this.form.member_nrw          =[]
            this.form.member_hessen        =[]
            this.form.member_rhein_pfalz   =[]
            this.form.member_bayern       =[]
            
            


           
          }
       },

     updateBoxes(candiVec, selectedVec,tickObj) {
       /**
        * @param1  : candidateVector 
        * @param2  : selected vector 
        * @tickobj : ticks defined in the data. 
        * 
        * 
        */
        //
      
      // update the number of ticks...
      // this.ticks = this.icc_members.filter(box => this.form.president.includes(box.candidacy_id));
       tickObj.ticks = candiVec.filter(box => selectedVec.includes(box.candidacy_id));
      // console.log("tick length: "+tickObj.ticks.length);
      
      // re-enable checkboxes if back under the limit...
      if (tickObj.ticks.length < tickObj.limit) {
         candiVec.forEach(box => {
          if (!selectedVec.includes(box.post_id)) box.disabled = false;
        });
      }

      // disable empty checkboxes if at the limit...
      if (tickObj.ticks.length == tickObj.limit) {
        candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id)
          // console.log(selectedVec); 
          if (!selectedVec.includes(box.candidacy_id)) box.disabled = true;
        });
      }
     
    },
    create_candidates(pid){
      let candiArray =[]; 
        //let pres =this.candidacies.data.find(item=>item.candidacy_id===1) 
        let candi =this.candidacies.data.filter(item=>item.post_id===pid) 
      if(candi.length>1){
          candi[0].disabled =false
          candiArray  =candi
        }else{
          candi.disabled=false
          // candiArray.push(candi)
          candiArray=candi

        }
        console.log("candidate array:"+pid)
        console.log(candiArray.length);
        return candiArray;
    },
     find_selected_name(candiVec, selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_names =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id) 
          
          // console.log(selectedVec); 
          if (selectedVec.includes(box.candidacy_id))  selected_names.push(box.candidacy_name);
        });
        let  elected_names =selected_names.join(',');
        return elected_names

     },
      find_selected_user_id(candiVec,selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_ids =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id)
          // console.log(selectedVec); 
          if (selectedVec.includes(box.candidacy_id))  selected_ids.push(box.user_id);
        });
        let  elected_user_ids =selected_ids.join(',');
        return elected_user_ids

     },
      getSortOrder(prop) {    
    return function(a, b) {    
        if (a[prop] > b[prop]) {    
            return 1;    
        } else if (a[prop] < b[prop]) {    
            return -1;    
        }    
        return 0;    
    }
        
},
    SortByName(x,y) {
      return ((x.candidacy_name == y.candidacy_name) ? 0 : ((x.candidacy_name > y.candidacy_name) ? 1 : -1 ));
    },

    // Call Sort By Name
    
   
  },
  components:{
      ShowCandidate,
      JetInput,
      ShowCheckbox,
      JetValidationErrors,
      AppLayout,
      NrnaLayout  
  
  }
}
</script>
<style scopeed>
#first_vote_window{ 
  background-color :#C6FFC1;/* #EFF8FF; /** #BEDCFA; **/
}  
#second_vote_window{
  background-color : #BEDCFA; /*#B5EAEA; /* #B2EBF2; /** #BEDCFA; **/ 
}  

</style>