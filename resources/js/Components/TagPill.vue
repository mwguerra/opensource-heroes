<script setup>
import { computed } from 'vue';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    tag: {
        type: String,
        default: 'unknown'
    },
    rounded: {
        type: Boolean,
        default: false
    },
    pointer: {
        type: Boolean,
        default: false
    },
    canDelete: {
        type: Boolean,
        default: false
    },
    fullWidth: {
        type: Boolean,
        default: false
    },
});

defineEmits(['delete-request']);

const color = computed(() => {
    const hashtag = props.tag.indexOf('#');
    return (hashtag < 1 || hashtag === props.tag.length - 1) ? 'gray' : props.tag.slice(0, hashtag);
});

const tagValue = computed(() => {
    const hashtag = props.tag.indexOf('#');
    return (hashtag < props.tag.length - 1 && hashtag > 1) ? props.tag.slice(hashtag + 1) : props.tag;
});

const linkValue = computed(() => {
    const doublePercentage = props.tag.indexOf('%%');
    return (doublePercentage > 1 && doublePercentage < props.tag.length - 2) ? props.tag.slice(doublePercentage + 2) : null;
});
const hasLink = computed(() => !!linkValue.value);

function callLink(link) {
    if (props.canDelete) {
        return;
    }
}
</script>

<template>
    <div class="flex items-center" @click="callLink(linkValue)">
    <span
        :class="[
        color === 'gray' ? 'bg-gray-300 text-gray-800' : '',
        color === 'cyan' ? 'bg-cyan-300 text-cyan-800' : '',
        color === 'orange' ? 'bg-orange-300 text-orange-800' : '',
        color === 'yellow' ? 'bg-yellow-300 text-yellow-800' : '',
        color === 'green' ? 'bg-green-300 text-green-800' : '',
        color === 'red' ? 'bg-red-300 text-red-800' : '',
        color === 'indigo' ? 'bg-indigo-300 text-indigo-800' : '',
        pointer ? 'cursor-pointer' : '',
        fullWidth ? 'flex-1 text-center' : '',
        canDelete
          ? 'rounded-l-md'
          : rounded
            ? 'rounded-full'
            : 'rounded-md'
      ]"
        class="py-1 px-3"
    >
      {{ tagValue }}
    </span>
        <span v-if="canDelete" class="py-1 px-1.5 bg-red-400 rounded-r-md" @click.stop="$emit('delete-request')">
      <XMarkIcon :class="[fullWidth ? 'h-6' : 'h-4']" aria-hidden="true" class="w-4"/>
    </span>
    </div>
</template>
