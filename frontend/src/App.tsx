import './App.css'
import { Route, Routes } from 'react-router-dom'
import Navbar from './components/Navbar'
import PDP from './pages/ProductDisplay'
import ProductDetails from './pages/ProductDetails'
import { CartProvider } from './context/CartContext'; // Import CartProvider

const App: React.FC = () => (
  <CartProvider>
    <Routes>
      <Route path='/' element={<Navbar />}>
        <Route index element={<PDP />} />
        <Route path='/:category' element={<PDP />} />
        <Route path='/product/:id' element={<ProductDetails />} />
      </Route>
    </Routes>
  </CartProvider>
)

export default App
