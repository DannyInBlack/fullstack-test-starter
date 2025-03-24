export interface Product {
  id: string;
  name: string;
  inStock: boolean;
  gallery: string[];
  description: string;
  category: string;
  attributes: AttributeSet[];
  prices: Price[];
  brand: string;
}

export interface AttributeSet {
  id: string;
  name: string;
  type: string;
  items: Attribute[];
}

export interface Attribute {
  id: string;
  displayValue: string;
  value: string;
}

export interface Price {
  amount: number;
  currency: Currency;
}

export interface Currency {
  label: string;
  symbol: string;
}

