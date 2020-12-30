<footer class="footer">
    <div class="container-center">
        <?php
        $valorAno = '2017';
        $anoAtual = date_format(new DateTime(), 'Y');
        if ($anoAtual > $valorAno) {
            $valorAno .= ' - ' . $anoAtual;
        }
        ?>
        <div class="col-md-3 "></div>
        <div class="col-md-2"><img src="../imagens/secretaria_municipal_de_saude_2018.png" alt="Logo" width="240" height="40"/></div>
        <div class="col-md-5"><div class="footertext-muted v-center col-align--center">Secretaria Municipal da Sa&uacutede - Departamento de Inform&aacutetica - &copy <?php echo $valorAno; ?></div></div>
        <div class="col-md-2 "></div>
    </div>
</footer>
</body>
</html>