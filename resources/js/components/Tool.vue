<template>
    <div class="laravel-nova-configs">
        <heading class="mb-6">Configs</heading>

        <card class="bg-00 flex flex-row justify-start items-stretch" style="min-height: 300px">
            <div class="bg-40">
                <ul class="laravel-nova-configs__menu">
                    <li v-for="(configKey) in Object.keys(configKeys)" :key="configKey" v-bind:class="{'active': a === configKey}" v-on:click="setActiveConfig(configKey)">
                        {{ configKey }}
                    </li>
                </ul>
            </div>
            <div class="flex-grow laravel-nova-configs__content">
                <h1 class="text-4xl py-6 px-8 text-90 border-b border-40 font-light laravel-nova-configs__title">
                    {{ a }}
                </h1>
                <div class="form-group flex border-b border-40" v-for="(field) in configKeys[a] || []" :key="field.name">
                    <div class="w-1/5 px-8 py-6">
                        <label :for="field.name" class="form-label form-label text-80 h-9 pt-2">{{ field.label }}</label>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <input v-if="field.type == 'text'" :id="field.name" :name="field.name" type="text" class="w-full form-control form-input form-input-bordered" v-model="configData[a][field.name]" :placeholder="field.placeholder" />

                        <input v-if="field.type == 'number'" :id="field.name" :name="field.name" type="number" class="w-full form-control form-input form-input-bordered" v-model="configData[a][field.name]" :min="field.min || 0" :max="field.max" :step="field.step" :placeholder="field.placeholder" />
                        
                        <textarea v-if="field.type == 'textarea'" :id="field.name" :name="field.name" class="w-full form-control form-textarea form-input-bordered" v-model="configData[a][field.name]" :rows="field.row" :placeholder="field.placeholder"></textarea>
                        
                        <select v-if="field.type == 'select'" :id="field.name" name="field.name" class="w-full form-control form-select form-input-bordered" v-model="configData[a][field.name]" :rows="field.row" :multiple="field.multiple">
                            <option v-if="field.placeholder">{{ field.placeholder }}</option>
                            <option v-for="(label, value) in field.options || {}" :key="value" :value="value">{{label}}</option>
                        </select>

                        <div v-if="field.type == 'checkbox' || field.type == 'radio'">
                            <div class="checkbox-item mb-2" v-for="(label, value) in field.options || []" :key="value">
                                <label><input :value="value" :type="field.type" :name="field.name" :checked="(configData[a][field.name] || []).indexOf(value) >= 0" v-on:change="updateConfigValue(field.name, (configData[a][field.name] || []).indexOf(value) >= 0 ? (configData[a][field.name] || []).filter(x => x !== value) : [...(configData[a][field.name] || []), value])" /> {{ label }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-30 flex justify-end px-8 py-4">
                    <button class="btn btn-default btn-primary" type="button" v-on:click="saveCurrentConfig">Save configs</button>
                </div>
            </div>
        </card>
    </div>
</template>

<script>
export default {
  data() {
    return {
      configKeys: [],
      configData: {},
      a: null
    };
  },
  methods: {
    setKeys(keys) {
      this.configKeys = keys;
      const d = this.configData;
      Object.keys(keys || {}).forEach(k => {
        if (!d[k]) {
          d[k] = {};
        }
        const fields = keys[k];
        if (fields && fields.length > 0) {
          fields.forEach(field => {
            if (d[k][field.name] === undefined) {
              d[k][field.name] = field.value;
            }
          });
        }
      });
      this.configData = { ...d };

      if (!this.a) {
        const [firstKey] = Object.keys(keys);
        this.setActiveConfig(firstKey);
      }
    },
    setData(data = []) {
      const d = {};
      data.forEach(element => {
        const [k, name] = element.key.split(".");

        if (!d[k]) {
          d[k] = {};
        }
        d[k][name] = element.value;
      });

      this.configData = { ...this.configData, ...d };
    },
    setActiveConfig(key) {
      this.a = key;
    },
    updateConfigValue(name, value) {
      const k = this.a;

      this.configData = {
        ...this.configData,
        [k]: {
          ...this.configData[k],
          [name]: value
        }
      };
    },
    saveCurrentConfig() {
      const k = this.a;
      const data = this.configData[k];

      axios
        .put("/nova-vendor/laravel-nova-configs/save", {
          fields: data,
          key: k
        })
        .then(({ data }) => {
          console.log("data:", data);
        });
    }
  },
  mounted() {
    axios
      .get("/nova-vendor/laravel-nova-configs/setup")
      .then(({ data: { keys = [], data = [] } = {} }) => {
        this.setData(data);

        this.setKeys(keys);
      });
  }
};
</script>

<style>
/* Scoped Styles */
</style>
