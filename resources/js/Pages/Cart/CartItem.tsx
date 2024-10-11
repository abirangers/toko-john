import { Product } from "@/types";
import { buttonVariants } from "@/Components/ui/button";
import { Link } from "@inertiajs/react";
import { cn, formatPrice } from "@/lib/utils";
import { toast } from "sonner";

const CartItem = ({ product }: { product: Product }) => {
    return (
        <div className="flex justify-between p-4 bg-white border rounded-lg shadow-sm">
            <div className="flex gap-x-4">
                <div className="w-24 h-24 rounded-md md:w-32 md:h-32">
                    <img
                        src={product.image}
                        alt={product.title}
                        className="object-cover w-full h-full rounded-md"
                    />
                </div>
                <div>
                    <h2 className="text-base font-semibold md:text-lg text-primary">
                        {product.title}
                    </h2>
                    <p className="my-1 text-sm text-muted-foreground md:my-0 md:hidden">
                        {product.category.name}
                    </p>
                    <h3 className="text-base md:text-base text-secondary">
                        {formatPrice(product.price)}
                    </h3>
                </div>
            </div>

            <p className="hidden text-sm text-muted-foreground md:block">
                {product.category.name}
            </p>

            <div>
                <Link
                    href={route("cart.destroy", {
                        product_id: product.id,
                    })}
                    method="delete"
                    onSuccess={() => {
                        toast.success(
                            "Product removed from cart: " + product.title
                        );
                    }}
                    preserveScroll={true}
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
