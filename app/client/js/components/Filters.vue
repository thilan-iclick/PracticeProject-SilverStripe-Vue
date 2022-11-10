<template>
  <div id="filters">
    <label for="offLeash">
      <input
        type="checkbox"
        v-model="offLeash"
        @change="changeOnOffLeash"
        value=true
        name="filterOnOffLeash"
        id="offLeash">
      <img src="dog-off-leash.png" height="32">
      Off-leash
    </label>
    <label for="onLeash">
      <input
        type="checkbox"
        v-model="onLeash"
        @change="changeOnOffLeash"
        value="On-leash"
        name="filterOnOffLeash"
        id="onLeash">
      <img src="dog-on-leash.png" height="32">
      On-leash
    </label>
  </div>
</template>

<script>
export default {
  data() {
    return {
      onLeash: true,
      offLeash: true,
    };
  },
  methods: {
    changeOnOffLeash() {
      if (this.onLeash && this.offLeash) {
        this.$emit('changeOnOffLeash', '');
      } else if (this.onLeash) {
        this.$emit('changeOnOffLeash', 'On-leash');
      } else if (this.offLeash) {
        this.$emit('changeOnOffLeash', 'Off-leash');
      } else {
        this.$emit('changeOnOffLeash', 'x');
      }
    },
  },
};
</script>

<style>
  #filters {
    position: absolute;
    top: 20px;
    left: 20px;
    min-height: 40px;
    min-width: 100px;
    background-color: #eeeeee;
    padding: 10px 20px;
  }
  #filters label {
    display: block;
    height: 42px;
    line-height: 42px;
  }
  #filters label img, #filters label span {
    display: inline-block;
    width: 32px;
    height: 32px;
    vertical-align: middle;
    margin-bottom: 6px;
  }
</style>
