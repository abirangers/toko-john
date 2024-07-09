import { Bar, BarChart, CartesianGrid, XAxis } from "recharts";
import {
    ChartConfig,
    ChartContainer,
    ChartTooltip,
    ChartTooltipContent,
    ChartLegend,
    ChartLegendContent,
} from "@/Components/ui/chart";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { useState, useEffect } from "react";
import axios from "axios";

const chartConfig = {
    orders: {
        label: "Orders",
        color: "#2563eb",
    },
} satisfies ChartConfig;

export default function Chart({
    initialOrdersPerMonth,
    initialSelectedTimeRange,
}: {
    initialOrdersPerMonth: { month: string; orders: number }[];
    initialSelectedTimeRange: string;
}) {
    const [selectedOption, setSelectedOption] = useState(
        initialSelectedTimeRange
    );
    const [ordersPerMonth, setOrdersPerMonth] = useState(initialOrdersPerMonth);

    useEffect(() => {
        const fetchData = async () => {
            const response = await axios.get("/api/dashboard", {
                params: { timeRange: selectedOption },
            });
            setOrdersPerMonth(response.data.data);
        };

        fetchData();
    }, [selectedOption]);

    return (
        <div className="chart-wrapper">
            <div className="chart-header flex justify-between items-center mb-4">
                <h2 className="text-xl font-semibold">Total Order</h2>
                <Select
                    value={selectedOption}
                    onValueChange={setSelectedOption}
                >
                    <SelectTrigger className="max-w-[200px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="past 12 months">
                            Past 12 Months
                        </SelectItem>
                        <SelectItem value="past 6 months">
                            Past 6 Months
                        </SelectItem>
                        <SelectItem value="past 3 months">
                            Past 3 Months
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <ChartContainer
                config={chartConfig}
                className="min-h-[200px] w-full"
            >
                <BarChart accessibilityLayer data={ordersPerMonth}>
                    <CartesianGrid vertical={false} />
                    <XAxis
                        dataKey="month"
                        tickLine={false}
                        tickMargin={10}
                        axisLine={false}
                        tickFormatter={(value) => value.slice(0, 3)}
                    />
                    <ChartTooltip content={<ChartTooltipContent />} />
                    <ChartLegend content={<ChartLegendContent />} />
                    <Bar
                        dataKey="orders"
                        fill="var(--color-orders)"
                        radius={4}
                    />
                </BarChart>
            </ChartContainer>
        </div>
    );
}
