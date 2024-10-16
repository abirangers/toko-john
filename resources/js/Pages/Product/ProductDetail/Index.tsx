import Navbar from "@/Components/Navbar/Navbar";
import { Button } from "@/Components/ui/button";
import { ShoppingCart } from "lucide-react";
import React, { useState } from "react";
import { PageProps, Product } from "@/types";
import { router } from "@inertiajs/react";
import { toast } from "sonner";
import { formatPrice } from "@/lib/utils";

interface ProductDetailProps extends PageProps {
    product: Product;
}

const ProductDetail = ({ auth, product }: ProductDetailProps) => {
    const [selectedSize, setSelectedSize] = useState("");
    const { title, description, price, image, category } = product;

    const handleAddToCart = (
        e: React.MouseEvent<HTMLButtonElement, MouseEvent>,
        product: Product
    ) => {
        e.preventDefault();

        router.post(
            route("cart.store"),
            { product_id: product.id },
            {
                onSuccess: (params) => {
                    const flash = params.props.flash as unknown as {
                        success: string;
                        error: string;
                    };

                    if (flash.success) {
                        toast.success(flash.success);
                    }

                    if (flash.error) {
                        toast.error(flash.error);
                    }
                },
            }
        );
    };

    const handleSizeClick = (size: string) => {
        setSelectedSize(size);
    };

    return (
        <>
            <Navbar user={auth.user} />
            <section className="px-8 mt-4">
                <div className="flex justify-center gap-x-8">
                    {/* left */}
                    <div className="max-w-xl w-full flex-shrink-0 h-[496px] rounded-sm">
                        <img
                            src={image}
                            alt={title}
                            className="object-cover h-full rounded-sm"
                        />
                    </div>
                    {/* right */}
                    <div className="w-1/2 pt-5">
                        <div className="border-b">
                            <h1 className="mb-2 text-4xl font-bold leading-tight tracking-tighter text-primary">
                                {title}
                            </h1>
                            <h2 className="mb-3 text-2xl font-semibold leading-tight tracking-tighter text-secondary">
                                {formatPrice(price)}
                            </h2>
                            <p className="mb-4 text-sm font-normal text-muted-foreground">
                                {category.name}
                            </p>
                        </div>
                        <div className="pt-4">
                            <h3 className="mb-2 text-base font-semibold leading-tight">
                                Description:
                            </h3>
                            <p className="text-base font-normal text-muted-foreground">
                                {description}
                            </p>
                        </div>
                        <Button
                            onClick={(e) => handleAddToCart(e, product)}
                            className="mt-4 rounded-full gap-x-2"
                        >
                            Add to cart <ShoppingCart />
                        </Button>
                    </div>
                </div>
            </section>
        </>
    );
};

export default ProductDetail;
