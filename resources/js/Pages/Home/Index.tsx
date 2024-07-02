import { Category, PageProps, Product } from "@/types";
import HeroSection from "@/Pages/Home/Section/Hero";
import FeaturedCategoriesSection from "@/Pages/Home/Section/FeaturedCategories";
import PopularProducts from "@/Pages/Home/Section/PopularProducts";
import MainLayout from "@/Layouts/MainLayout";
import { usePage } from "@inertiajs/react";

const HomePage = ({
    auth,
    categories,
    products,
}: PageProps<{ categories: Category[]; products: Product[] }>) => {
    return (
        <MainLayout user={auth.user}>
            {/*=============== Hero ==================*/}
            <HeroSection />

            {/*=============== Featured Categories ==================*/}
            <FeaturedCategoriesSection categories={categories} />

            {/*=============== Popular Products ==================*/}
            <PopularProducts products={products} />
        </MainLayout>
    );
};

export default HomePage;
