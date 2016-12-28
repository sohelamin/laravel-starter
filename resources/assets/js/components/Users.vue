<template>
    <div>
        <crudify label="User" :url="url" :fields="fields" :columns="columns"></crudify>
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
                url: '/admin/users',
                fields: [
                    {
                        "name": "name",
                        "type": "text"
                    },
                    {
                        "name": "email",
                        "type": "email"
                    },
                    {
                        "name": "password",
                        "type": "password"
                    },
                    {
                        "name": "roles",
                        "type": "select",
                        "options": [
                            {
                                "name": "admin",
                                "label": "Administrator",
                            },
                            {
                                "name": "user",
                                "label": "User"
                            }
                        ]
                    }
               ],
                columns: [
                    'name', 'email'
                ]
            }
        }
    }
</script>
