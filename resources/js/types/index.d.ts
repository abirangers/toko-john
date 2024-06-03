export interface User {
    id: string;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Category {
    id: string;
    name: string;
    products: Product[];
}

export interface Product {
    id: string;
    title: string;
    description: string;
    price: number;
    image: string;
    category: Category;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
