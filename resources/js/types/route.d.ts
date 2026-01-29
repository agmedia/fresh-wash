declare function route(
    name: string,
    params?: Record<string, any> | any[],
    absolute?: boolean,
): string;