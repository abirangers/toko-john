type OrderStatus = "pending" | "completed" | "canceled";

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    slug: string;
    carts: Cart[];
}

export interface Category {
    id: number;
    name: string;
    products: Product[];
    slug: string;
}

export interface Product {
    id: number;
    title: string;
    description: string;
    price: number;
    stock: number;
    image: string;
    category: Category;
    slug: string;
}

export interface Order {
    id: number;
    order_items: OrderItem[];
    status: OrderStatus;
    total_price: number;
}

export interface OrderItem {
    id: number;
    order_id: number;
    product: Product;
    price: number;
}

export interface CartItem {
    id: number;
    cart_id: number;
    product_id: number;
    product: Product;
    quantity: number;
}

export interface Cart {
    user_id: number;
    cart_items: CartItem[];
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
