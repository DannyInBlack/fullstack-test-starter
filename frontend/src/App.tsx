import './App.css'
import { Route, Routes } from 'react-router-dom'
import Navbar from './components/Navbar'
import PDP from './pages/ProductDisplay'

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path='/' element={<PDP />}/>
        <Route path='/product/:id' element={<PDP />}/>
      </Routes>
    </>
  )
}

export default App
