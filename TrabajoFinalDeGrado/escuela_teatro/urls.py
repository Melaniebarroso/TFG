from django.urls import path
from . import views

urlpatterns = [
    path('', views.inicio, name='inicio'),
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
    path('administracion/<int:admin_id>/inscribir_alumno/<int:curso_id>/', views.inscribir_alumno, name='inscribir_alumno'),
    path('administracion/<int:id>/pedidos/<int:pedido_id>/', views.detalle_pedido, name='detalle_pedido'),
    path('administracion/<int:admin_id>/curso/<int:curso_id>/subir_material/', views.subir_material, name='subir_material'),
    path('administracion/<int:admin_id>/eliminar_inscripcion/<int:inscripcion_id>/', views.eliminar_inscripcion, name='eliminar_inscripcion'),
    path('administracion/<int:admin_id>/eliminar_alumno/<int:alumno_id>/', views.eliminar_alumno, name='eliminar_alumno'),

    path("login/", views.login_view, name="login"),
    path('logout/', views.CustomLogoutView.as_view(), name='logout'),

    path('generar_factura_pdf/', views.generar_factura_pdf, name='generar_factura_pdf'),

]