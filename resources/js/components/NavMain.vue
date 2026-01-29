<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        items: NavItem[];
        label?: string;
    }>(),
    {
        label: 'Platform',
    },
);

// expose for script + template usage
const { items, label } = props;

const page = usePage();

type NavItemWithChildren = NavItem & { children?: NavItem[] };

const hasChildren = (item: NavItemWithChildren) =>
    Array.isArray(item.children) && item.children.length > 0;

const isItemActive = (href: string) => urlIsActive(href, page.url);

const isParentActive = (item: NavItemWithChildren) => {
    if (isItemActive(item.href)) return true;
    if (!hasChildren(item)) return false;
    return item.children!.some((c) => isItemActive(c.href));
};

const openState = ref<Record<string, boolean>>({});

const keyFor = (item: NavItemWithChildren) => item.href || item.title;

const getOpen = (item: NavItemWithChildren) => {
    const key = keyFor(item);
    return openState.value[key] ?? isParentActive(item);
};

const toggleOpen = (item: NavItemWithChildren) => {
    const key = keyFor(item);
    openState.value[key] = !(openState.value[key] ?? isParentActive(item));
};

onMounted(() => {
    // initialize open states for parents based on current URL
    for (const item of (items as unknown as NavItemWithChildren[])) {
        if (hasChildren(item)) {
            openState.value[keyFor(item)] = isParentActive(item);
        }
    }
});

watch(
    () => page.url,
    () => {
        // when navigation happens, ensure the active parent is opened
        for (const item of (items as unknown as NavItemWithChildren[])) {
            if (hasChildren(item) && isParentActive(item)) {
                openState.value[keyFor(item)] = true;
            }
        }
    },
);
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>{{ label }}</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in (items as unknown as NavItemWithChildren[])" :key="item.title">
                <!-- Normal item -->
                <SidebarMenuItem v-if="!hasChildren(item)">
                    <SidebarMenuButton
                        as-child
                        :is-active="isItemActive(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>

                <!-- Accordion item (parent) -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        class="cursor-pointer"
                        :is-active="isParentActive(item)"
                        :tooltip="item.title"
                        @click="toggleOpen(item)"
                    >
                        <component :is="item.icon" />
                        <span class="flex-1">{{ item.title }}</span>
                        <ChevronDown
                            class="h-4 w-4 transition-transform duration-200"
                            :class="getOpen(item) ? 'rotate-180' : ''"
                        />
                    </SidebarMenuButton>

                    <div v-if="getOpen(item)" class="mt-1 pl-6">
                        <SidebarMenu class="gap-0">
                            <SidebarMenuItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuButton
                                    as-child
                                    :is-active="isItemActive(child.href)"
                                    :tooltip="child.title"
                                >
                                    <Link :href="child.href">
                                        <component :is="child.icon" />
                                        <span>{{ child.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </div>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
