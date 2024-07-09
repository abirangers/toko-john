import React from "react";
import { buttonVariants } from "../ui/button";
import { ShoppingCart } from "lucide-react";
import { Link, usePage } from "@inertiajs/react";
import { cn } from "@/lib/utils";
import { PageProps } from "@/types";

const CartIcon = () => {
    const { auth } = usePage().props as unknown as PageProps<{
        auth: { user: { carts: { cart_items: { length: number }[] }[] } };
    }>;
    const totalCartItems = auth.user?.carts[0]?.cart_items.length || 0;

    return (
        <Link
            className={cn(
                buttonVariants({
                    size: "sm",
                    variant: "outline",
                }),
                "gap-x-1 text-secondary rounded-full"
            )}
            aria-label="items-in-cart"
            href={route("cart.index")}
        >
            <ShoppingCart className="w-4 h-4" />
            {totalCartItems}
        </Link>
    );
};

export default CartIcon;
