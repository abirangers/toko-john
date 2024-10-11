import Navbar from "@/Components/Navbar/Navbar";
import { Button, buttonVariants } from "@/Components/ui/button";
import { Category, PageProps, Product, User } from "@/types";
import { Link, router, usePage } from "@inertiajs/react";
import { Plus } from "lucide-react";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";
import { cn } from "@/lib/utils";
import ProductCard from "@/Components/ProductCard";
import { useState } from "react";
import MainLayout from "@/Layouts/MainLayout";
import { Input } from "@/Components/ui/input";

interface ProductProps extends PageProps {
    products: Product[];
    categories: Category[];
}

const ProductPage = ({ products, categories }: ProductProps) => {
    const { auth, categoryParams } = usePage().props as unknown as {
        auth: { user: User };
        categoryParams: string;
    };

    const [_, setSelectedCategory] = useState(categoryParams);
    const [searchTerm, setSearchTerm] = useState("");

    const handleCategoryClick = (categoryName: string) => {
        setSelectedCategory(categoryName);
        router.get(
            categoryName === categoryParams
                ? "/products"
                : "/products?category=" + categoryName
        );
    };

    const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearchTerm(e.target.value);
    };

    const filteredProducts = products.filter((product) =>
        product.title.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <MainLayout user={auth.user}>
            <section className="px-8 pt-10 mx-auto max-w-7xl">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h2 className="mb-1 text-3xl font-bold tracking-tighter text-primary">
                            Products ({filteredProducts.length})
                        </h2>
                        <p className="text-sm font-normal text-muted-foreground">
                            Explore our top-selling products.
                        </p>
                    </div>
                    <div className="flex flex-col gap-y-3">
                        <FilterSheet
                            categories={categories}
                            categoryParams={categoryParams}
                            handleCategoryClick={handleCategoryClick}
                        />
                        <Input
                            type="text"
                            placeholder="Search products..."
                            value={searchTerm}
                            onChange={handleSearch}
                        />
                    </div>
                </div>

                {filteredProducts.length == 0 ? (
                    <div>
                        <p className="text-base font-normal text-muted-foreground">
                            No products found
                        </p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-[72px]">
                        {filteredProducts.map((product, index) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                )}
            </section>
        </MainLayout>
    );
};

const FilterSheet = ({
    categories,
    categoryParams,
    handleCategoryClick,
}: {
    categories: Category[];
    categoryParams: string;
    handleCategoryClick: (categoryName: string) => void;
}) => {
    return (
        <Sheet>
            <SheetTrigger
                className={cn(
                    buttonVariants({ size: "sm" }),
                    "flex items-center justify-center w-24 ml-auto"
                )}
            >
                <span className="mr-2">Filters</span>{" "}
                <Plus className="w-4 h-4" />
            </SheetTrigger>
            <SheetContent>
                <SheetHeader>
                    <SheetTitle className="pb-4 border-b border-gray-300 text-secondary">
                        Categories
                    </SheetTitle>
                    <SheetDescription className="pt-3 mx-auto space-x-2">
                        {categories?.map((category, i) => (
                            <Button
                                key={i}
                                className="rounded-full"
                                variant={
                                    category.name.toLowerCase() ==
                                    categoryParams
                                        ? "default"
                                        : "outline"
                                }
                                onClick={() =>
                                    handleCategoryClick(
                                        category.name.toLowerCase()
                                    )
                                }
                            >
                                {category.name}
                            </Button>
                        ))}
                    </SheetDescription>
                </SheetHeader>
            </SheetContent>
        </Sheet>
    );
};

export default ProductPage;
