<template>
    <div class="tabber">
        <div v-for="tab in tabs"
             :key="tab.name"
             @click.prevent="setActive(tab.name)"
             :class="{active: tab.name == value}"
        >
            {{tab.text}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tabber",
        props: {
            tabs: {
                type: Array,
                default() {
                    return [
                        {
                            text: 'Yes',
                            name: 'yes'
                        }, {
                            text: 'Yes',
                            name: 'no'
                        }]
                }
            },
            value: {
                default: null
            }
        },
        methods: {
            setActive(tab) {
                this.$emit('input', tab);
            }
        }
    }
</script>

<style scoped>

</style>