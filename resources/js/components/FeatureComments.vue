<template>
    <div class="space-y-4">
        <div v-for="comment in comments" :key="comment.id" class="bg-muted p-4 rounded-lg space-y-2">
            <p class="tet-muted-foreground">{{ comment.content }}</p>
            <small class="tet-muted-foreground/75 text-xs">{{ comment.author_name }} - {{ new Intl.DateTimeFormat('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date(comment.created_at)) }}</small>
        </div>
    </div>
</template>

<script>
export default {
    name: "FeatureComments",
    props: {
        comments: {
            type: Array,
            default: () => []
        }
    }
}
</script>
