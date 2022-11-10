<template>
  <div class="park" v-if="park">
    <a href="/" @click.exact.prevent="$router.push('/')">Close</a>
    <h2>{{ park.Title }}</h2>
    <ul class="park__features">
      <li>{{ park.OffLeash ? "Off-Leash" : "On-Leash" }}</li>
    </ul>
    <p class="park__notes">{{ park.Notes }}</p>
    <p class="park__provider">Managed by <strong>{{ park.Council }}</strong></p>
    <hr/>
    <div class="uploadfld">
      <h4>Send us your Dog's Photo</h4>
      <label>File
        <input type="file" accept="image/*" @change="handleFileUpload( $event )"/>
      </label>
      <br/><br/>
      <button v-on:click="submitFile()">Submit for Approval</button>
    </div>
    <div v-if="park.dogPhoto" >
      <hr/>
      <img class="dogimg" v-bind:src="park.dogPhoto" />
    </div>
  
  </div>
</template>

<script>
import axios from "axios";

export default {
  data(){
      return {
        file: ''
      }
    },
  computed: {
    park() {
      return this.$store.state.parks.find(park => park.ID === parseInt(this.$route.params.id, 10));
    },
    parks() {
      return this.$store.state.parks;
    },
  },
  methods: {
      /*
        Submits the file to the server
      */
      submitFile(){
        /*
                Initialize the form data
            */
            let formData = new FormData();

            /*
                Add the form data we need to submit
            */
            formData.append('file', this.file);
            formData.append('pid',parseInt(this.$route.params.id, 10));

        /*
          Make the request
        */
            axios.post( 'api/v1/upload',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
            ).then(function(){
          alert("Your Photo successfully submitted to us. We will make it live after approval.");
         // console.log('file uploaded');
        })
        .catch(function(){
          alert("oops.. Something went wrong..");
         // console.log('FAILURE!!');
        });
      },

      /*
        Handles a change on the file upload
      */
      handleFileUpload(event){
      //  console.log(event.target.files[0]);
        this.file = event.target.files[0];
      }
    }
};
</script>

<style>
  .park {
    padding: 20px;
  }
  .uploadfld{
    text-align: center !important;
  }
  .dogimg{
    width: 100% !important;
  }
</style>
