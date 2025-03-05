<?php 
namespace App\Builders;

use Illuminate\Support\Collection;
class FillterProductBuilder{

    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }
    /**
     * filter product price > min and price < max
     *
     * @param int $min
     * @param int $max
     * @return self
     */
    public function filterPrice($min, $max): self
    {
        $this->products = $this->products->map(function ($product) {
            if ($product->option_type == 0) {
                $product->load('variations');
            }
            return $product;
        });

        // Filter products based on price range
        $this->products = $this->products->filter(function ($product) use ($min, $max) {
            if ($product->option_type == 1) {
                $finalPrice = $this->handlePrice($product->price, $product->sale);
                return $finalPrice >= $min && $finalPrice <= $max;
            } else if ($product->option_type == 0) {
                $minVariationPrice = $product->variations->min(function ($variation) {
                    return $this->handlePrice($variation->price, $variation->sale);
                });
                return $minVariationPrice >= $min && $minVariationPrice <= $max;
            }
            return false;
        });

        return $this;
    }


    /**
     * filter product material
     * 
     * @param array $material 
     * @return self
     */
    public function filterMetarial(array $material): self
    {
        if (!empty($material)) {

            $this->products->where(function ($query) use ($material) {
                foreach ($material as $singleMaterial) {
                    $query->whereJsonContains('material', $singleMaterial);
                }
            });
        }

        return $this;
    }

    /**
     * handle price 
     *
     * @param integer $price
     * @param integer $sale
     * @return int
     */
    protected function HandlePrice($price, $sale): int
    {
        $finalPrice = (int)($price - ($price * $sale / 100));

        return $finalPrice;
    }


    /**
     * sort products by price
     *
     * @param integer $sort_price
     * @return self
     */
    public function sortPrice($sort_price): self
    {
        if ($sort_price == 1) {
            $this->products = $this->products->sortBy(function ($product) {
                if ($product->option_type == 1) {
                    return $this->handlePrice($product->price, $product->sale);
                } elseif ($product->option_type == 0) {
                    return $product->variations->min(function ($variation) {
                        return $this->handlePrice($variation->price, $variation->sale);
                    });
                }
            });

        } elseif ($sort_price == 2) {
            $this->products = $this->products->sortByDesc(function ($product) {
                if ($product->option_type == 1) {
                    return $this->handlePrice($product->price, $product->sale);
                } elseif ($product->option_type == 0) {
                    return $product->variations->min(function ($variation) {
                        return $this->handlePrice($variation->price, $variation->sale);
                    });
                }
            });
        }
    
        return $this;
    
    }


    /**
     * Sort products by favourite
     *
     * @param integer $sort_favourite
     * @return self
     */
    public function filterSortFavourite($sort_favourite): self
    {
        if ($sort_favourite == 1) {
            $this->products = $this->products->sortBy(function ($product) {
                return $this->calculateProductScore($product);
            });
        } elseif ($sort_favourite == 2) {
            $this->products = $this->products->sortByDesc(function ($product) {
                return $this->calculateProductScore($product);
            });
        }

        return $this;
    }


    /**
     * sort product by Sale
     *
     * @param integer $sort_sale
     * @return self
     */
    public function filterSortSale($sort_sale): self
    {
        if ($sort_sale == 1) {
            $this->products = $this->products->sortBy(function ($product) {
                if ($product->option_type == 1) {
                    return $product->sale;
                } elseif ($product->option_type == 0) {
                    return $product->variations->min('sale');
                }
            });
        } elseif ($sort_sale == 2) {
            $this->products = $this->products->sortByDesc(function ($product) {
                if ($product->option_type == 1) {
                    return $product->sale;
                } elseif ($product->option_type == 0) {
                    return $product->variations->max('sale');
                }
            });
        }
    
        return $this;
    }

    /**
     * function handle score product 
     *
     * @param object $product
     * @return float
     */
    protected function calculateProductScore($product) :float
    {
        $score = 100;

        // Tương tác của người dùng
        $score += $product->favourite * 2;

        // Thời gian đăng
        $timeDiff = now()->diffInMinutes($product->created_at);
        $score += max(0, 100 - $timeDiff);

        // Cộng điểm dựa trên rate (giả sử rate tối đa là 5)
        $score += ($product->total_rate - 3) * 10; // Mỗi điểm rate trên 3 cộng thêm 10 điểm, dưới 3 trừ đi 10 điểm

        // Cộng điểm dựa trên số lượng bán
        if ($product->quantity_saled > 100) {
            $score += 20; // Bán trên 100
        } elseif ($product->quantity_saled > 50) {
            $score += 10; // Bán trên 50
        } else {
            $score -= 10; // Bán dưới 50
        }

        return $score;
    }

    /**
     * Perform any final processing or transformations.
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function build(): ?Collection
    {
        // Perform any final processing or transformations if needed
        // Example: $this->products = $this->products->map(function ($product) {
        //             $product->formatted_price = '$' . number_format($product->price, 2);
        //             return $product;
        //         });

        return $this->products->isEmpty() ? null : $this->products;
    }



}
