import { Product } from "@/types";
import { buttonVariants } from "@/Components/ui/button";
import { Link, usePage } from "@inertiajs/react";
import { cn, formatPrice } from "@/lib/utils";
import { useEffect } from "react";
import { toast } from "sonner";

const CartItem = ({ product }: { product: Product }) => {
    return (
        <div className="flex justify-between">
            <div className="flex gap-x-6">
                <div className="w-24 h-24 rounded-md md:w-48 md:h-48">
                    <img
                        src={`/storage/${product.image}`}
                        alt="product1"
                        className="object-cover h-full rounded-md"
                    />
                </div>
                <div>
                    <h2 className="text-base font-semibold md:text-lg">
                        {product.title}
                    </h2>
                    <p className="my-1 text-sm text-primary/70 md:my-0 md:hidden">
                        {product.category.name}
                    </p>
                    <h3 className="text-base md:text-base">
                        {formatPrice(product.price)}
                    </h3>
                </div>
            </div>

            <p className="hidden text-sm text-primary/70 md:block">
                {product.category.name}
            </p>

            <div>
                <Link
                    href={route("cart.removeFromCart", {
                        productId: product.id,
                    })}
                    method="delete"
                    onSuccess={() => {
                        toast.success(
                            "Product removed from cart: " + product.title
                        );
                    }}
                    className={cn(
                        buttonVariants({
                            variant: "outline",
                        }),
                        "w-8 h-8 leading-8 text-center rounded-full shadow-md"
                    )}
                >
                    X
                </Link>
            </div>
        </div>
    );
};

export default CartItem;
