import { Link } from "@inertiajs/react";
import { cn } from "@/lib/utils";
import { buttonVariants } from "@/Components/ui/button";
import { ArrowRight, ShoppingCart } from "lucide-react";
import { Product } from "@/types";
import ProductCard from "@/Components/ProductCard";

const PopularProductsSection = ({ products }: { products: Product[] }) => {
    return (
        <section id="popularProduct" className="px-8 pt-24">
            <div className="mb-8">
                <h2 className="mb-3 text-3xl font-bold leading-tight tracking-normal sm:mb-4 sm:text-4xl md:text-5xl text-secondary">
                    Popular Products
                </h2>
                <div className="flex justify-between">
                    <p className="text-base font-normal sm:text-lg md:mb-4 text-muted-foreground">
                        Jelajahi semua produk yang kami tawarkan dari SMK Plus Pelita Nusantara!
                    </p>
                    <Link
                        className="hidden text-base transition-all md:flex text-secondary gap-x-1 hover:translate-x-1 hover:text-secondary/80"
                        href={""}
                    >
                        Shop the collection{" "}
                        <ArrowRight className="w-6 h-6 text-secondary" />
                    </Link>
                </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-[72px]">
                {products.map((product) => (
                    <ProductCard key={product.id} product={product} />
                ))}
            </div>

            <Link
                href={"/products"}
                className={cn(
                    buttonVariants(),
                    "mx-auto text-center flex w-fit hover:before:-translate-x-48"
                )}
            >
                View all products <ArrowRight />
            </Link>
        </section>
    );
};

export default PopularProductsSection;
