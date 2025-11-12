<template>
    <button
        @click="$emit('vote')"
        :class="[
            'vfb-button',
            { 'bg-accent text-accent-foreground': hasVoted }
        ]"
    >
        <span>{{ votesCount }} {{ labels.votes }}</span>
    </button>
</template>

<script>
export default {
    name: "FeatureVote",
    props: {
        votesCount: {
            type: Number,
            required: true
        },
        hasVoted: {
            type: Boolean,
            default: false
        },
        labels: {
            type: Object,
            required: true
        }
    }
}
</script>
