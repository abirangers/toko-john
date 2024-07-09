import { Link } from "@inertiajs/react";
import { ArrowRight } from "lucide-react";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardImage,
    CardTitle,
} from "@/Components/ui/card";
import { Category } from "@/types";

const FeaturedCategoriesSection = ({
    categories,
}: {
    categories: Category[];
}) => {
    return (
        <section id="category" className="px-8 pt-24">
            <FeaturedCategoriesHeader />

            <div className="grid gap-4 sm:grid-flow-col sm:grid-rows-2 md:grid-rows-1">
                {categories.map((category, index) => (
                    <FeaturedCategoryCard
                        key={category.id}
                        category={category}
                    />
                ))}
            </div>
        </section>
    );
};

const FeaturedCategoriesHeader = () => {
    return (
        <div className="mb-8">
            <h2 className="max-w-md mb-3 text-3xl font-bold sm:mb-4 sm:text-4xl md:text-5xl leading-[1.1]">
                Featured Categories
            </h2>
            <div className="flex justify-between">
                <p className="font-normal leading-normal sm:text-lg md:mb-4 text-muted-foreground">
                    Discover our top-rated hospital equipment.
                </p>
                <Link
                    className="hidden text-base transition-all md:flex gap-x-1 hover:translate-x-1 hover:text-primary/80"
                    href={route("product.index")}
                >
                    Explore the collection <ArrowRight className="w-6 h-6" />
                </Link>
            </div>
        </div>
    );
};

const FeaturedCategoryCard = ({ category }: { category: Category }) => {
    return (
        <Link
            href={`/products?category=${category.name.toLowerCase()}`}
            className="group"
        >
            <Card className="transition-all group-hover:bg-primary">
                <CardHeader>
                    <CardImage
                        src={`/images/${category.name.toLowerCase()}.png`}
                        className="w-8 h-8"
                    />
                </CardHeader>
                <CardContent>
                    <CardTitle className="text-primary mb-[6px] text-2xl font-semibold tracking-tight group-hover:text-primary-foreground">
                        {category.name}
                    </CardTitle>
                    <CardDescription className="group-hover:text-secondary-foreground">
                        {category.products.length} Product
                    </CardDescription>
                </CardContent>
            </Card>
        </Link>
    );
};

export default FeaturedCategoriesSection;
