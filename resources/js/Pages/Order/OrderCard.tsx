import { formatPrice } from "@/lib/utils";
import { Product } from "@/types";
import { Link } from "@inertiajs/react";

const OrderCard = ({ product }: { product: Product }) => {
    return (
        <div className="flex p-4 bg-white rounded-md shadow-sm gap-x-6">
            <div className="w-36 h-36">
                <img
                    src={product.image}
                    alt={product.title}
                    className="object-cover w-full h-full rounded-md text-secondary"
                />
            </div>

            <div className="flex flex-col justify-between w-full space-y-3">
                <div>
                    <Link
                        href={route("product.show", product.slug)}
                        className="text-lg font-semibold text-primary hover:underline"
                    >
                        {product.title}
                    </Link>
                    <p className="text-sm text-muted-foreground">
                        {product.category.name}
                    </p>
                </div>
                <p className="text-base text-secondary">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                    Totam magnam necessitatibus id facilis explicabo enim fugit,
                    atque aspernatur impedit odit! Velit vel cum dolores
                    accusamus deleniti animi omnis corporis totam.
                </p>
                <div className="flex justify-between">
                    <span className="text-lg font-semibold text-secondary">
                        {formatPrice(product.price)}
                    </span>
                </div>
            </div>
        </div>
    );
};

export default OrderCard;
