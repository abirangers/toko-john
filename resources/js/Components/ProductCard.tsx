import { ShoppingCart } from "lucide-react";
import { Link, router, usePage } from "@inertiajs/react";
import { Product } from "@/types";
import React from "react";
import { toast } from "sonner";
import { formatPrice } from "@/lib/utils";

const ProductCard = ({ product }: { product: Product }) => {
    const handleAddToCart = (
        e: React.MouseEvent<HTMLDivElement, MouseEvent>,
        product: Product
    ) => {
        e.preventDefault();

        router.post(
            route("cart.addToCart"),
            { productId: product.id },
            {
                onSuccess: (params) => {
                    const flash = params.props.flash as {
                        success: string;
                        error: string;
                    };

                    if (flash.success) {
                        toast.success(flash.success);
                    } else if (flash.error) {
                        toast.error(flash.error);
                    }
                },
                onError: (params) => {
                    console.log(params);
                },
            }
        );
    };

    return (
        <Link
            href={route("product.show", product.slug)}
            className="p-4 transition-all bg-white border rounded-lg shadow-lg hover:shadow-xl"
        >
            <img
                src={`/storage/${product.image}`}
                alt={product.title}
                className="object-cover w-full mb-4 rounded-xl h-60"
            />
            <p className="mb-1 text-sm font-medium text-gray-600">
                {product.category.name}
            </p>
            <h2 className="mb-2 text-lg font-semibold text-gray-800">
                {product.title}
            </h2>
            <div className="flex items-center justify-between">
                <h3 className="text-base font-semibold text-gray-9000">
                    {formatPrice(product.price)}
                </h3>
                <div className="group">
                    <div
                        onClick={(e) => handleAddToCart(e, product)}
                        className="p-2 transition-all bg-gray-100 border rounded-full shadow-md group-hover:bg-secondary"
                    >
                        <ShoppingCart className="w-5 h-5 text-gray-800 group-hover:text-white" />
                    </div>
                </div>
            </div>
        </Link>
    );
};

export default ProductCard;
