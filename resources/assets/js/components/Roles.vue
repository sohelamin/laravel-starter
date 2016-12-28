<template>
    <div>
        <crudify label="Role" :url="url" :fields="fields" :columns="columns"></crudify>
    </div>
</template>

<script>
    var Crudify = require('./Crudify.vue');

    export default {
        components: {
            'crudify': Crudify
        },
        data() {
            return {
                url: '/admin/roles',
                fields: [
                    {
                        "name": "name",
                        "type": "text"
                    },
                    {
                        "name": "label",
                        "type": "text"
                    }
               ],
                columns: [
                    'name', 'label'
                ]
            }
        }
    }
</script>
