<script setup>
import { ref } from 'vue';
import TagPill from "@/Components/TagPill.vue"; // Ensure this import matches your actual component name and path

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Add Tag'
    },
    readOnly: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const addTagValue = ref('');

const addTag = (tag) => {
    if (!tag) {
        return;
    }

    emit('update:modelValue', [...props.modelValue, tag]);
    addTagValue.value = '';
};

const deleteTag = (tagToDelete) => {
    emit('update:modelValue', props.modelValue.filter(tag => tag !== tagToDelete));
};
</script>

<template>
    <div
        class="w-full flex text-xs border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 border rounded-md items-center flex-wrap"
    >
        <TagPill
            v-for="(tag, index) in modelValue" :key="index"
            :can-delete="!readOnly"
            :tag="tag"
            class="p-1 mr-1"
            @delete-request="deleteTag(tag)"
        />
        <input
            v-if="!readOnly"
            v-model="addTagValue" :placeholder="placeholder"
            class="border-none focus:ring-0 focus:border-none text-sm flex-1 rounded-md"
            type="text"
            @focusout="addTag(addTagValue)"
            @keypress.enter.prevent="addTag(addTagValue)"
        >
        <span v-if="readOnly && modelValue.length === 0" class="text-sm py-2 px-3">Nothing Here</span>
    </div>
</template>
