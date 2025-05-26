from django.urls import path
from . import views

urlpatterns = [
    path('', views.inicio, name='inicio'),
    path('laTara', views.laTara, name='laTara'),
    path('blog', views.blog, name='blog'),
    path('blog/<int:pk>/', views.post_detalle, name='post_detalle'),
    path('contacto', views.contacto, name='contacto'),
    path('espectaculos', views.espectaculos, name='espectaculos'),
    path('educacion', views.educacion, name='educacion'),
    path('tienda', views.tienda, name='tienda'),
    path('agregar/<int:producto_id>/', views.agregar_al_carrito, name='agregar_al_carrito'),
    path('carrito/', views.ver_carrito, name='ver_carrito'),
    path('eliminar/<int:producto_id>/', views.eliminar_del_carrito, name='eliminar_del_carrito'),
    path('checkout/', views.checkout, name='checkout'),


    
    path('login', views.login_view, name='login'),
    path('perfil/<int:user_id>/', views.perfil_view, name='perfil'),
    path('administracion/<int:id>/', views.admin_dashboard, name='administracion'),
    path('administracion/<int:id>/pedidos/', views.lista_pedidos, name='lista_pedidos'),
    path('administracion/<int:id>/pedidos/<int:pedido_id>/', views.detalle_pedido, name='detalle_pedido'),
    path('administracion/<int:id>/curso/<int:curso_id>/subir_material/', views.subir_material, name='subir_material'),


    # URLs para Contacto
    #path('contactos/', views.ContactoListView.as_view(), name='contacto_list'),

    #URLs para Login
    path("login/", views.login_view, name="login"),
    #path("perfil/<int:id>/", views.perfil, name="perfil"),
    #path('registro/', views.registro, name='registro'),
    #path("login/", views.CustomLogoutView.as_view(), name="logout"),


]