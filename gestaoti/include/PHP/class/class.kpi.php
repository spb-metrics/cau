<?php
/*
Copyright 2011 da EMBRATUR
 Este arquivo é parte do programa CAU - Central de Atendimento ao Usuário
 O CAU é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela 
 Fundação do Software Livre (FSF); na versão 2 da Licença.
 Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
 Observe no diretório gestaoti/install/ a cópia da Licença Pública Geral GNU, sob o título "licensa_uso.htm". 
 Se preferir acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
/*
* -------------------------------------------------------
* CLASSNAME:        kpi
* -------------------------------------------------------
*/

include_once("include/PHP/class/class.database.postgres.php");
include_once("include/PHP/class/class.chamado.php");
include_once("include/PHP/class/class.situacao_chamado.php");
include_once("include/PHP/class/class.parametro.php");
include_once("include/PHP/class/class.pagina.php");
include_once("include/PHP/class/class.dados_agrupados.php");

// **********************
// CLASS DECLARATION
// **********************
class kpi{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $rowCount;
	var $vQtdRegistros;
	var $graph;
	var $dados;
	var $label;
	var $legend;
	var $valorOdometro;
	var $situacao_chamado;
	var $DTH_INICIO;
	var $DTH_FIM;
	var $SEQ_EQUIPE_TI;
	var $SEQ_TIPO_CHAMADO;
	var $arFaixaOdometro;
	var $fileName;
	var $sql;
	var $contForaPrazo;
	var $cont;
	var $parametro;
	var $pagina;
	var $SEQ_CENTRAL_ATENDIMENTO; 

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function kpi(){
		$this->database = new Database();
		$this->dados = array();
		$this->label = array();
		$this->legend= array();
		
		$this->arFaixaOdometro = array();
		$this->arFaixaOdometro[0][0] = "0";
		$this->arFaixaOdometro[0][1] = "35";
		$this->arFaixaOdometro[0][2] = "red";

		$this->arFaixaOdometro[1][0] = "35";
		$this->arFaixaOdometro[1][1] = "50";
		$this->arFaixaOdometro[1][2] = "yellow";

		$this->arFaixaOdometro[2][0] = "50";
		$this->arFaixaOdometro[2][1] = "100";
		$this->arFaixaOdometro[2][2] = "green";

		$this->fileName = "";

		$this->parametro = new parametro();
		$this->pagina = new pagina();
	}

	// **********************
	// GETTER METHODS
	// **********************
	function getrowCount(){
		return $this->rowCount;
	}

	function getvQtdRegistros(){
		return $this->vQtdRegistros;
	}

	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}

	// Criar gráfico de linha simples
	function GraficoLinhaSimples($datay, $datax, $titulo="", $cor="blue", $aWidth=450, $aHeight=250, $xTitle="", $yTitle="", $LeftMargin=60, $RightMargin=20, $TopMargin=30, $BottomMargin=80){
		if(count($datay) >= 2){
			include_once("include/PHP/class/jpgraph.php");
			include_once("include/PHP/class/jpgraph_line.php");
			//Exemplo: $datay  = array(11,3, 8,12,5 ,1,9, 13,5,7 );

			// Criar o gráfico.
			$this->graph  = new Graph($aWidth, $aHeight, "auto");
			$this->graph->SetScale("textlin");

			// Adicionar os valores de x
			$this->graph->xaxis->SetTickLabels($datax);
			$this->graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
			$this->graph->xaxis->SetLabelAngle(30);

			// Configurar margens e títulos
			$this->graph->img->SetMargin($LeftMargin, $RightMargin, $TopMargin, $BottomMargin);
			if($titulo != ""){
				$this->graph->title->Set($titulo);
			}
			if($xTitle != ""){
				$this->graph->xaxis->title->Set($xTitle);
			}
			if($yTitle != ""){
				$this->graph->yaxis->title->Set($yTitle);
			}

			// Criar a plotagem de linha
			$lineplot = new LinePlot($datay);
			$lineplot->SetColor($cor);

			// Adicionando uma marca em cada ponto
			$lineplot->mark->SetType(MARK_UTRIANGLE);

			// Adionando valores aos pontos
			$lineplot->value->Show();

			// Adicionar a plotagem ao gráfico
			$this->graph->Add($lineplot);

			// Mostrar o gráfico
			if($this->fileName != ""){
				$this->graph->Stroke($this->fileName);
			}else{
				$this->graph->Stroke();
			}
		}else{
			$this->imgErro("ERRO\nNão é possível carregar gráfico de \nlinhas com menos de 2 pontos.");
		}
	}
	
	// Criar gráfico de multi linhas simples
	function GraficoMultiLinhaSimples($datay, $datax, $titulo="", $cor="blue", $aWidth=450, $aHeight=250, $xTitle="", $yTitle="", $LeftMargin=60, $RightMargin=20, $TopMargin=30, $BottomMargin=80){
		if(count($datay) >= 2){
			include_once("include/PHP/class/jpgraph.php");
			include_once("include/PHP/class/jpgraph_line.php");
			//Exemplo: $datay  = array(11,3, 8,12,5 ,1,9, 13,5,7 );

			// Criar o gráfico.
			$this->graph  = new Graph($aWidth, $aHeight, "auto");
			$this->graph->SetScale("textlin");

			// Adicionar os valores de x
			$this->graph->xaxis->SetTickLabels($datax);
			$this->graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
			$this->graph->xaxis->SetLabelAngle(30);

			// Configurar margens e títulos
			$this->graph->img->SetMargin($LeftMargin, $RightMargin, $TopMargin, $BottomMargin);
			if($titulo != ""){
				$this->graph->title->Set($titulo);
			}
			if($xTitle != ""){
				$this->graph->xaxis->title->Set($xTitle);
			}
			if($yTitle != ""){
				$this->graph->yaxis->title->Set($yTitle);
			}
			
			for ($i=0;$i < count($datay);$i++){
				
				// Criar a plotagem de linha
				$lineplot = new LinePlot($datay[$i]->dados);
				//$lineplot->SetColor($cor);
				$lineplot->SetColor($datay[$i]->getCor());

				// Adicionando uma marca em cada ponto
				//$lineplot->mark->SetType(MARK_UTRIANGLE);
				$lineplot->mark->SetType($i+1);		
				$lineplot->mark->SetWidth(5);	
				//$lineplot->mark->SetWeight(5);				

				// Adionando valores aos pontos
				$lineplot->value->Show();
				
				$lineplot->SetLegend($datay[$i]->legenda);

				// Adicionar a plotagem ao gráfico
				$this->graph->Add($lineplot);
				
			}
			

			// Mostrar o gráfico
			if($this->fileName != ""){
				$this->graph->Stroke($this->fileName);
			}else{
				$this->graph->Stroke();
			}
		}else{
			$this->imgErro("ERRO\nNão é possível carregar gráfico de \nlinhas com menos de 2 pontos.");
		}
	}

	// Criar gráfico de barras verticais simples
	function GraficoBarrasSimples($datay, $datax, $titulo="", $cor="blue", $aWidth=450, $aHeight=300, $xTitle="", $yTitle="", $LeftMargin=40, $RightMargin=20, $TopMargin=30, $BottomMargin=120){
		if(count($datay) > 0){
			include_once("include/PHP/class/jpgraph.php");
			include_once("include/PHP/class/jpgraph_bar.php");
			//Exemplo: $datay  = array(11,3, 8,12,5 ,1,9, 13,5,7 );

			// Criar o gráfico.
			$this->graph  = new Graph($aWidth, $aHeight, "auto");
			$this->graph->SetScale("textlin");

			// Adicionar sombra
			//$this->graph->SetShadow();

			// Adicionar os valores de x
			$this->graph->xaxis->SetTickLabels($datax);
			$this->graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
			$this->graph->xaxis->SetLabelAngle(30);

			// Configurar margens e títulos
			$this->graph->img->SetMargin($LeftMargin, $RightMargin, $TopMargin, $BottomMargin);
			if($titulo != ""){
				$this->graph->title->Set($titulo);
			}
			if($xTitle != ""){
				$this->graph->xaxis->title->Set($xTitle);
			}
			if($yTitle != ""){
				$this->graph->yaxis->title->Set($yTitle);
			}

			// Criar uma plotagem de Barras
			$bplot = new BarPlot($datay);

			// Adjust fill color
			$bplot->SetFillColor('orange');
			$bplot->value->Show();

			// Adiocionar ao gráfico
			$this->graph->Add($bplot);

			// Mostrar o gráfico
			if($this->fileName != ""){
				$this->graph->Stroke($this->fileName);
			}else{
				$this->graph->Stroke();
			}
		}else{
			$this->imgErro("ERRO\nDados não encontrados.");
		}
	}

	// Criar gráfico de pizza simples
	function GraficoPizzaSimples($datay, $datax, $titulo="", $cor="blue", $aWidth=450, $aHeight=250, $LeftMargin=10, $RightMargin=10, $TopMargin=30, $BottomMargin=10){
		if(count($datay) > 0){
			include_once("include/PHP/class/jpgraph.php");
			include_once("include/PHP/class/jpgraph_pie.php");
			//Exemplo: $datay  = array(11,3, 8,12,5 ,1,9, 13,5,7 );

			// Criar o gráfico.
			$this->graph  = new PieGraph($aWidth, $aHeight, "auto");
			$this->graph->SetScale("textlin");

			// Adicionar sombra
			//$this->graph->SetShadow();

			// Configurar margens e títulos
			$this->graph->img->SetMargin($LeftMargin, $RightMargin, $TopMargin, $BottomMargin);
			if($titulo != ""){
				$this->graph->title->Set($titulo);
			}

			// Adicionar plotagem
			$p1 = new PiePlot($datay);
			$p1->SetLegends($datax);
			$p1->SetCenter(0.32);

			$this->graph->Add($p1);

			if($this->fileName != ""){
				$this->graph->Stroke($this->fileName);
			}else{
				$this->graph->Stroke();
			}
		}else{
			$this->imgErro("ERRO\nDados não encontrados.");
		}
	}

	// Gráfico odometro
	function GraficoOdometro($valor, $titulo="", $subtitulo="", $aWidth=450, $aHeight=250, $caption=""){
		include_once("include/PHP/class/jpgraph_odometro.php");
		include_once("include/PHP/class/jpgraph_odo.php");

		// Criar um novo gráfico odometro
		$this->graph = new OdoGraph($aWidth, $aHeight);
		$this->graph->SetColor("white");
		//$this->graph->SetBorder("black", 1);
		// Especificar título e subtítulo
		if($titulo != ""){
			$this->graph->title->Set($titulo);
			$this->graph->title->SetColor("white");

		}

		if($subtitulo != ""){
			$this->graph->subtitle->Set($subtitulo);
			$this->graph->subtitle->SetColor("white");
		}

		// Especificar um texto para a parte de baixo do gráfico
		if($caption != ""){
			$this->graph->caption->Set($caption);
			$this->graph->caption->SetColor("white");
		}

		// Criar um odometro e adicionar ao gráfico - escala automática de 0 a 100
		$odo = new Odometer();

		// Colocar as faixas
		for($w=0; $w < count($this->arFaixaOdometro); $w++){
			$odo->AddIndication($this->arFaixaOdometro[$w][0],$this->arFaixaOdometro[$w][1], $this->arFaixaOdometro[$w][2]);
		}

		// Valor mostrado
		$odo->needle->Set($valor);

		// Adicionar o odometro ao gráfico
		$this->graph->Add($odo);

		if($this->fileName != ""){
			$this->graph->Stroke($this->fileName);
		}else{
			$this->graph->Stroke();
		}
	}

	// Mensagem de erro em imagem
	function imgErro($txt, $aWidth=450, $aHeight=250, $LeftMargin=5, $RightMargin=5, $TopMargin=5, $BottomMargin=5){
		include_once("include/PHP/class/jpgraph.php");
		include_once("include/PHP/class/jpgraph_canvas.php");

		// Setup a basic canvas we can work
		$g = new CanvasGraph($aWidth, $aHeight, 'auto');
		$g->SetMargin($LeftMargin, $RightMargin, $TopMargin, $BottomMargin);
		//$g->SetShadow();
		//$g->SetMarginColor("teal");

		// We need to stroke the plotarea and margin before we add the
		// text since we otherwise would overwrite the text.
		$g->InitFrame();

		// Draw a text box in the middle
		$t = new Text($txt,200,10);
		//$t->SetFont(FF_ARIAL,FS_BOLD,10);
		$t->SetFont(FF_ARIAL,FS_NORMAL,9);

		// How should the text box interpret the coordinates?
		$t->Align('center','top');

		// How should the paragraph be aligned?
		$t->ParagraphAlign('center');

		// Add a box around the text, white fill, black border and gray shadow
		$t->SetBox("white","white","white");

		// Stroke the text
		$t->Stroke($g->img);

		// Stroke the graph
		$g->Stroke();
	}

	/*
	-----------------------------------------------------------------------------------
	-- Início das consultas dos indicadores
	-- Visão Operacional - KPI Pesquisadas por Dependência/Equipe/Profissional e Período
	-----------------------------------------------------------------------------------
	*/

	//-----------------------------------------------------------------------------------
	//-- Quantidade de chamados por Situação
	function KpiChamadosPorSituacao(){
		$sql = "select count(1) as QTD_CHAMADOS, DSC_SITUACAO_CHAMADO
				from(
				  SELECT  DISTINCT a.SEQ_CHAMADO, c.DSC_SITUACAO_CHAMADO
				  FROM gestaoti.chamado a LEFT OUTER JOIN gestaoti.atribuicao_chamado b on (a.SEQ_CHAMADO = b.SEQ_CHAMADO), 
				  gestaoti.situacao_chamado c, gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e, gestaoti.tipo_chamado f
				  WHERE a.SEQ_SITUACAO_CHAMADO = c.SEQ_SITUACAO_CHAMADO
				  and a.SEQ_SITUACAO_CHAMADO = c.SEQ_SITUACAO_CHAMADO
				  and a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO." 
				  and f.FLG_UTILIZADO_SLA='S'
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			      and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " ) a
		          group by DSC_SITUACAO_CHAMADO
				  order by DSC_SITUACAO_CHAMADO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	//-----------------------------------------------------------------------------------
	//-- Quantidade de chamados por Tipo de Ocorrência
	function KpiChamadosPorTipoOcorrencia(){
		$sql = "select count(a.SEQ_CHAMADO) as QTD_CHAMADOS, NOM_TIPO_OCORRENCIA
				FROM 
				gestaoti.chamado a, gestaoti.tipo_ocorrencia c, gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e, gestaoti.tipo_chamado f
				  where a.SEQ_TIPO_OCORRENCIA = c.SEQ_TIPO_OCORRENCIA
				  and a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and f.FLG_UTILIZADO_SLA='S'	
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_TIPO_OCORRENCIA
			   	  order by NOM_TIPO_OCORRENCIA ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = strlen($row[1])>20?substr($row[1], 0, strpos($row[1]," ")):$row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamado por Prioridade
	function KpiChamadosPorPrioridade(){
		$sql = "select count(1) as QTD_CHAMADOS, DSC_PRIORIDADE_CHAMADO
				from(
				  select distinct a.SEQ_CHAMADO, c.DSC_PRIORIDADE_CHAMADO
				  FROM gestaoti.chamado a LEFT OUTER JOIN gestaoti.atribuicao_chamado b ON (a.SEQ_CHAMADO = b.SEQ_CHAMADO), 
				  gestaoti.prioridade_chamado c, gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e , gestaoti.tipo_chamado f
				  where a.SEQ_PRIORIDADE_CHAMADO = c.SEQ_PRIORIDADE_CHAMADO
				  and a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and f.FLG_UTILIZADO_SLA='S'	
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " ) a
				group by DSC_PRIORIDADE_CHAMADO
				order by DSC_PRIORIDADE_CHAMADO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por lotação
	function KpiChamadosPorLotacao(){
		$sql = "select count(1) as QTD_CHAMADOS, LOTACAO
				from(
				  select distinct a.SEQ_CHAMADO, c.dep_sigla as lotacao
				  FROM gestaoti.chamado a LEFT OUTER JOIN gestaoti.atribuicao_chamado b ON (a.SEQ_CHAMADO = b.SEQ_CHAMADO), 
				  gestaoti.viw_age_empregados c, gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e, gestaoti.tipo_chamado f
				  where a.NUM_MATRICULA_SOLICITANTE = c.NUM_MATRICULA_RECURSO
				  and a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and f.FLG_UTILIZADO_SLA='S'	
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " ) a
				group by LOTACAO
				order by LOTACAO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por sistema de informação
	function KpiChamadosPorSistemaInformacao(){
		$sql = "select count(1) as QTD_CHAMADOS, a.SIG_ITEM_CONFIGURACAO
				from(
				  select distinct a.SEQ_CHAMADO,
					case c.SIG_ITEM_CONFIGURACAO
					when '' then
						'N/A'
					ELSE
						c.SIG_ITEM_CONFIGURACAO
					END as SIG_ITEM_CONFIGURACAO
				  FROM gestaoti.chamado a LEFT OUTER JOIN gestaoti.item_configuracao c on (a.seq_item_configuracao = c.seq_item_configuracao)
										  LEFT OUTER JOIN gestaoti.atribuicao_chamado b on (a.SEQ_CHAMADO = b.SEQ_CHAMADO)
					, gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e, gestaoti.tipo_chamado f					  
				  WHERE 
				  a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.FLG_UTILIZADO_SLA='S'					  
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			      and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= "
				) a
				group by a.SIG_ITEM_CONFIGURACAO
				order by a.SIG_ITEM_CONFIGURACAO  ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1]==""?"N/A":$row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Status do SLA (Em dia, Risco de atraso e Atrasado)
	function KpiChamadosPorSLA(){
		$sql = "select
			  		to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
					a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO,
					to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo
				FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				and b.seq_subtipo_chamado = c.seq_subtipo_chamado
				and c.seq_tipo_chamado = d.seq_tipo_chamado
				and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and d.FLG_UTILIZADO_SLA='S'	
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$result = $this->database->query($sql);
		$aDadosAux = array();
		$banco = new chamado();
		$pagina = new pagina();
		while ($row = pg_fetch_array($this->database->result)){
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			$aDadosAux[] = $banco->fGetDSC_SLA_ATENDIMENTO($v_COD_SLA_ATENDIMENTO);
		}

		$this->dados = array();
		$this->label = array();
		$cont = 0;
		for($i=0; $i<count($aDadosAux); $i++){
			if($pagina->arrayFind($this->label, $aDadosAux[$i]) == 0){
				$this->label[] = $aDadosAux[$i];
				$this->dados[] = 1;
			}else{
				// Adicionar 1 no ao registro
				for($j = 0; $j < count($aDadosAux); $j++){
					if($this->label[$j] == $aDadosAux[$i]){
						$this->dados[$j] = $this->dados[$j] + 1;
					}
				}
			}
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Status do SLA (Em dia, Risco de atraso e Atrasado)
	function KpiChamadosPorSLANivel1(){
		$this->sql = " select
					  		to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
							a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO,
							to_char(a.dth_triagem_efetiva, 'dd/mm/yyyy hh24:mi:ss') as dth_triagem_efetiva,
							to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo
						FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
						WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
						and b.seq_subtipo_chamado = c.seq_subtipo_chamado
						and c.seq_tipo_chamado = d.seq_tipo_chamado
						and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			   			and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
					    and d.FLG_UTILIZADO_SLA = 'S'
					    and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
						and a.dth_triagem_efetiva is not null
						and a.dth_encerramento_efetivo is not null
						and b.QTD_MIN_SLA_TRIAGEM is not null ";
		$result = $this->database->query($this->sql);
		$aDadosAux = array();
		$banco = new chamado();
		$pagina = new pagina();
		while ($row = pg_fetch_array($this->database->result)){
			// Se o chamado não foi fechado no primeiro nível
			if(substr($row["dth_triagem_efetiva"], 0, 16) != substr($row["dth_encerramento_efetivo"], 0, 16)){
				$contForaPrazo++;
			}else{
				$v_DTH_TRIAGEM_PREVISAOO = $banco->fGetDTH_TRIAGEM_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_triagem"]==""?30:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
				$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_TRIAGEM_PREVISAOO, $row["dth_triagem_efetiva"], $row["qtd_min_sla_triagem"]);
				if($v_COD_SLA_ATENDIMENTO == -1){
					$contForaPrazo++;
				}
			}
			$cont++;
		}
		$this->cont = $cont;
		$this->contForaPrazo = $contForaPrazo;
		//print "contForaPrazo = $contForaPrazo | cont = $cont | ".($contForaPrazo/$cont)*100;
		if($contForaPrazo > 0){
			$this->valorOdometro = (1-($contForaPrazo/$cont))*100;
		}else{
			$this->valorOdometro = 0;
		}
	}

	function TempoMedioNivel1(){
		$this->sql = "select
			  		to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
			  		to_char(a.DTH_ABERTURA, 'mm/yyyy') as MES_ABERTURA,
					a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO,
					to_char(a.dth_triagem_efetiva, 'dd/mm/yyyy hh24:mi:ss') as dth_triagem_efetiva,
					to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo
				FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				and b.seq_subtipo_chamado = c.seq_subtipo_chamado
				and c.seq_tipo_chamado = d.seq_tipo_chamado
				and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and a.DTH_ABERTURA between  to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
			    and d.FLG_UTILIZADO_SLA='S'
				and a.dth_triagem_efetiva is not null
				and a.dth_encerramento_efetivo is not null
				and b.QTD_MIN_SLA_TRIAGEM is not null
				order by a.dth_triagem_efetiva";
		$result = $this->database->query($this->sql);
		$aDadosAux = array();
		$aDadosAux2 = array();
		$banco = new chamado();
		$pagina = new pagina();
		//===================================================================================
		while ($row = pg_fetch_array($this->database->result)){
			$aDadosAux[] = $row["mes_abertura"];//."-".$row["seq_chamado"];
			$aDadosAux2[] = $pagina->dateDiffMinutosUteis($row["dth_abertura"], $row["dth_triagem_efetiva"], $banco->HoraInicioExpediente, $banco->HoraInicioIntervalo, $banco->HoraFimIntervalo, $banco->HoraFimExpediente, $banco->aDtFeriado);
		}

		$this->dados = array();
		$dados1 = array();
		$this->label = array();
		$cont = 0;
		for($i=0; $i<count($aDadosAux); $i++){
			//procurar no array
//			print "<br>label=".$aDadosAux[$i]." | Dados=".$aDadosAux2[$i];
			if($pagina->arrayFind($this->label, $aDadosAux[$i]) == 0){
				$this->label[] = $aDadosAux[$i];
				$this->dados[] += $aDadosAux2[$i];
				$dados1[] = 1;
			}else{
				// Adicionar 1 no ao registro
				for($j = 0; $j < count($aDadosAux); $j++){
					if($this->label[$j] == $aDadosAux[$i]){
						$this->dados[$j] += $aDadosAux2[$i];
						$dados1[$j] += 1;
					}
				}
			}
		}
//		print "<hr>";
		// Calcular as médias
		for($j = 0; $j < count($this->label); $j++){
//			print "<br>label=".$this->label[$j]." | Dados=".$this->dados[$j]." | Dados 2:".$dados1[$j];
			$this->dados[$j] = $this->dados[$j] / $dados1[$j];
			$this->valorOdometro = $this->dados[$j];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados atribuídos para cada Dependência
	function KpiChamadosPorDependencia(){
		$sql = "select count(1) as QTD_CHAMADOS, sg_dependencia
				from(
				  select distinct a.SEQ_CHAMADO, d.sg_dependencia
				  FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b, gestaoti.equipe_ti c, gestaoti.viw_diretoria d
				  where a.SEQ_CHAMADO = b.SEQ_CHAMADO
				  and b.SEQ_EQUIPE_TI = c.SEQ_EQUIPE_TI
				  and c.COD_DEPENDENCIA = d.cd_dependencia
				  and b.SEQ_EQUIPE_TI = 1
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			     and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
				) a
				group by sg_dependencia
				order by sg_dependencia ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados atribuídos para cada Equipe
	function KpiChamadosPorEquipe(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_EQUIPE_TI
				from(
				  select distinct a.SEQ_CHAMADO, c.NOM_EQUIPE_TI
				  FROM gestaoti.chamado a LEFT OUTER JOIN gestaoti.atribuicao_chamado b ON (a.SEQ_CHAMADO = b.SEQ_CHAMADO)
							  			  LEFT OUTER JOIN gestaoti.equipe_ti c ON (b.SEQ_EQUIPE_TI = c.SEQ_EQUIPE_TI)
					 , gestaoti.atividade_chamado d, gestaoti.subtipo_chamado e		, gestaoti.tipo_chamado f	  
				  WHERE 
				    a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
				  and d.seq_subtipo_chamado = e.seq_subtipo_chamado
				  and c.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and e.seq_tipo_chamado = f.seq_tipo_chamado
				  and f.FLG_UTILIZADO_SLA='S'
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			      and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and b.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI." ";
		}
		$sql .= "
				) a
				group by NOM_EQUIPE_TI
				order by NOM_EQUIPE_TI ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1]==""?"Sem Atribuição":$row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados atribuídos para cada Profissional
	function KpiChamadosPorProfissional(){
		//and b.SEQ_EQUIPE_TI = 1 retirado o filtro da equipe de qualidade
		$sql = "select count(1) as QTD_CHAMADOS, NOM_COLABORADOR
				from(
				  select distinct a.SEQ_CHAMADO, c.NOM_COLABORADOR as NOM_COLABORADOR
				  FROM gestaoti.chamado a,
					gestaoti.atribuicao_chamado b LEFT OUTER JOIN gestaoti.viw_colaborador c on (b.NUM_MATRICULA = c.NUM_MATRICULA_COLABORADOR),
					gestaoti.equipe_ti d
				  where a.SEQ_CHAMADO = b.SEQ_CHAMADO
				  and b.seq_equipe_ti = d.seq_equipe_ti
				  /* and b.SEQ_EQUIPE_TI = 1 */
				  and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
				) a
				group by NOM_COLABORADOR
				order by NOM_COLABORADOR";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de horas de trabalho registradas por profissional
	function KpiHorasPorProfissional(){
		$sql = "select to_char(DTH_FIM - DTH_INICIO,'ss') as QTD_SEGUNDOS_DURACAO,
					   c.NOM_COLABORADOR as NOM_COLABORADOR
				FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b, gestaoti.viw_colaborador c, gestaoti.time_sheet d,gestaoti.equipe_ti e
				where a.SEQ_CHAMADO = b.SEQ_CHAMADO
				and b.NUM_MATRICULA = c.NUM_MATRICULA_COLABORADOR
				and b.NUM_MATRICULA = d.NUM_MATRICULA
				and b.seq_equipe_ti = e.seq_equipe_ti
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				/* and b.SEQ_EQUIPE_TI = 1 */
			  	and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
				order by NOM_COLABORADOR";
		$result = $this->database->query($sql);
		$aDadosAux = array();
		$aNomesAux = array();
		$banco = new chamado();
		while ($row = pg_fetch_array($this->database->result)){
			$aDadosAux[] = $row[0];
			$aNomesAux[] = $row[1];
		}

		$this->dados = array();
		$this->label = array();
		$cont = 0;
		$pagina = new pagina();
		for($i=0; $i<count($aDadosAux); $i++){
			if($pagina->arrayFind($this->label, $aNomesAux[$i]) == 0){
				$this->label[] = $aNomesAux[$i];
				$this->dados[] = $aDadosAux[$i];
			}else{
				// Adicionar 1 no ao registro
				for($j = 0; $j < count($aNomesAux); $j++){
					if($this->label[$j] == $aNomesAux[$i]){
						$this->dados[$j] += $aDadosAux[$j];
					}
				}
			}
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de horas de trabalho registradas por equipe de ti
	function KpiHorasPorEquipe(){
		$sql = "select to_char(DTH_FIM - DTH_INICIO,'ss') as QTD_SEGUNDOS_DURACAO,
					   c.NOM_EQUIPE_TI
				FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b, gestaoti.equipe_ti c, gestaoti.time_sheet d,gestaoti.equipe_ti e
				where a.SEQ_CHAMADO = b.SEQ_CHAMADO
				and b.SEQ_EQUIPE_TI = c.SEQ_EQUIPE_TI
				and b.NUM_MATRICULA = d.NUM_MATRICULA
				and b.seq_equipe_ti = e.seq_equipe_ti
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
			  	and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
				order by NOM_EQUIPE_TI ";
		$result = $this->database->query($sql);
		$aDadosAux = array();
		$aNomesAux = array();
		$banco = new chamado();
		while ($row = pg_fetch_array($this->database->result)){
			$aDadosAux[] = $row[0];
			$aNomesAux[] = $row[1];
		}

		$this->dados = array();
		$this->label = array();
		$cont = 0;
		$pagina = new pagina();
		for($i=0; $i<count($aDadosAux); $i++){
			if($pagina->arrayFind($this->label, $aNomesAux[$i]) == 0){
				$this->label[] = $aNomesAux[$i];
				$this->dados[] = $aDadosAux[$i];
			}else{
				// Adicionar 1 no ao registro
				for($j = 0; $j < count($aNomesAux); $j++){
					if($this->label[$j] == $aNomesAux[$i]){
						$this->dados[$j] += $aDadosAux[$j];
					}
				}
			}
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Avaliação (Cliente) recebida - Satisfação com a solução apresentada
	function KpiChamadosPorAvaliacao(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e
				where a.SEQ_AVALIACAO_ATENDIMENTO = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO
				  order by NOM_AVALIACAO_ATENDIMENTO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	function KpiChamadosPorAvaliacaoEvolucaoMensal(){
		
		$sql = "SELECT 
		 			sum(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,seq_avaliacao_atendimento, DATA_ABERTURA
				FROM (
					SELECT	
			  			count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,b.seq_avaliacao_atendimento , to_char(a.DTH_ABERTURA,'MM/YYYY' )as DATA_ABERTURA
					FROM 
					 gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
					WHERE 
			 			a.SEQ_AVALIACAO_ATENDIMENTO =  b.SEQ_AVALIACAO_ATENDIMENTO 
			 			and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO 
			 			and c.seq_subtipo_chamado  = d.seq_subtipo_chamado 
			 			and d.seq_tipo_chamado = e.seq_tipo_chamado
						and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
			 			and e.FLG_UTILIZADO_SLA='S' 
			 			and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
	
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		
		$sql .= "	GROUP BY NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento, a.DTH_ABERTURA
					ORDER BY NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento, a.DTH_ABERTURA	";
		$sql .= ") AS a ";
		$sql .= "GROUP BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA
				 ORDER BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA ";
  
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		$dadosAgrupados = null;
		$Legenda = "";
		$PosicaoAtual = -1;
		
		while ($row = pg_fetch_array($this->database->result)){
			
			if($Legenda != $row[1]){
				$PosicaoAtual++;
				$this->dados[] = new DadosAgrupados();				 
				$this->dados[$PosicaoAtual]->SetLegenda($row[1]);
				$this->dados[$PosicaoAtual]->SetId($row[2]);
				$Legenda = $row[1];			 
			}			
			
			$this->dados[$PosicaoAtual]->addDados($row[0]); 
			
			if(!$this->existeLabel($row[3],$this->label)){
				$this->label[] = $row[3];
			}
			
		}
		
		$this->label = $this->ordenarLabel($this->label);
		//sort($this->label);
		//rsort($this->label);
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Avaliação (Cliente) recebida - Satisfação com o conhecimento do prestador
	function KpiChamadosPorAvaliacao_Satisfacao_Conhecimento(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_CONHECIMENTO_TECNICO = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO
				  order by NOM_AVALIACAO_ATENDIMENTO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	
	function KpiChamadosPorAvaliacao_Satisfacao_ConhecimentoEvolucaoMensal(){
		
		$sql  = "SELECT 
					sum(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,seq_avaliacao_atendimento, DATA_ABERTURA 
 				 FROM ( 			  ";
		$sql .= "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento , to_char (a.DTH_ABERTURA,'MM/YYYY' )as DATA_ABERTURA  
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_CONHECIMENTO_TECNICO = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO ,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA
				  order by NOM_AVALIACAO_ATENDIMENTO ,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA ";
		
		$sql  .= ") AS a
				GROUP BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA 
				ORDER BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		$dadosAgrupados = null;
		$Legenda = "";
		$PosicaoAtual = -1;
		
		while ($row = pg_fetch_array($this->database->result)){
			if($Legenda != $row[1]){
				$PosicaoAtual++;
				$this->dados[] = new DadosAgrupados();				 
				$this->dados[$PosicaoAtual]->SetLegenda($row[1]);
				$this->dados[$PosicaoAtual]->SetId($row[2]);
				$Legenda = $row[1];			 
			}			
			
			$this->dados[$PosicaoAtual]->addDados($row[0]); 
			
			if(!$this->existeLabel($row[3],$this->label)){
				$this->label[] = $row[3];
			}
			
		}
				
		$this->label = $this->ordenarLabel($this->label);
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Avaliação (Cliente) recebida - Satisfação com a postura e cordialidade do prestador
	function KpiChamadosPorAvaliacao_Satisfacao_Postura(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_POSTURA = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO
				  order by NOM_AVALIACAO_ATENDIMENTO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	
	function KpiChamadosPorAvaliacao_Satisfacao_PosturaEvolucaoMensal(){
		$sql  = "SELECT 
					sum(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,seq_avaliacao_atendimento, DATA_ABERTURA 
 				 FROM ( 			  ";
		
		$sql .= "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento , to_char (a.DTH_ABERTURA,'MM/YYYY' )as DATA_ABERTURA
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_POSTURA = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA
				  order by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA ";
		
		
		$sql  .= ") AS a
				GROUP BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA 
				ORDER BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA ";
		
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		$dadosAgrupados = null;
		$Legenda = "";
		$PosicaoAtual = -1;
		
		while ($row = pg_fetch_array($this->database->result)){
			if($Legenda != $row[1]){
				$PosicaoAtual++;
				$this->dados[] = new DadosAgrupados();				 
				$this->dados[$PosicaoAtual]->SetLegenda($row[1]);
				$this->dados[$PosicaoAtual]->SetId($row[2]);
				$Legenda = $row[1];			 
			}			
			
			$this->dados[$PosicaoAtual]->addDados($row[0]); 
			
			if(!$this->existeLabel($row[3],$this->label)){
				$this->label[] = $row[3];
			}
			
		}
				
		$this->label = $this->ordenarLabel($this->label);
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Avaliação (Cliente) recebida - Satisfação com o tempo de espera
	function KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_TEMPO_ESPERA = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO
				  order by NOM_AVALIACAO_ATENDIMENTO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	
	function KpiChamadosPorAvaliacao_Satisfacao_Tempo_EsperaEvolucaoMensal(){
		$sql  = "SELECT 
					sum(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,seq_avaliacao_atendimento, DATA_ABERTURA 
 				 FROM ( 			  ";
		
		$sql .= "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento , to_char (a.DTH_ABERTURA,'MM/YYYY' )as DATA_ABERTURA
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_TEMPO_ESPERA = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA
				  order by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA ";
		
		
		$sql  .= ") AS a
				GROUP BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA 
				ORDER BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		$dadosAgrupados = null;
		$Legenda = "";
		$PosicaoAtual = -1;
		
		while ($row = pg_fetch_array($this->database->result)){
			if($Legenda != $row[1]){
				$PosicaoAtual++;
				$this->dados[] = new DadosAgrupados();				 
				$this->dados[$PosicaoAtual]->SetLegenda($row[1]);
				$this->dados[$PosicaoAtual]->SetId($row[2]);
				$Legenda = $row[1];			 
			}			
			
			$this->dados[$PosicaoAtual]->addDados($row[0]); 
			
			if(!$this->existeLabel($row[3],$this->label)){
				$this->label[] = $row[3];
			}			
		}
				
		$this->label = $this->ordenarLabel($this->label);
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Avaliação (Cliente) recebida - Satisfação com o tempo de solução
	function KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao(){
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e	
				where a.SEQ_AVALIACAO_TEMPO_SOLUCAO = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO
				  order by NOM_AVALIACAO_ATENDIMENTO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	function KpiChamadosPorAvaliacao_Satisfacao_Tempo_SolucaoEvolucaoMensal(){
		$sql  = "SELECT 
					sum(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO ,seq_avaliacao_atendimento, DATA_ABERTURA 
 				 FROM ( 	";
		
		$sql .= "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento , to_char (a.DTH_ABERTURA,'MM/YYYY' )as DATA_ABERTURA
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e
				where a.SEQ_AVALIACAO_TEMPO_SOLUCAO = b.SEQ_AVALIACAO_ATENDIMENTO
				and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and e.FLG_UTILIZADO_SLA='S'
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		
		$sql .= " group by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA
				  order by NOM_AVALIACAO_ATENDIMENTO,b.seq_avaliacao_atendimento ,a.DTH_ABERTURA ";
		
		
		$sql  .= ") AS a
				GROUP BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA 
				ORDER BY  NOM_AVALIACAO_ATENDIMENTO,seq_avaliacao_atendimento,DATA_ABERTURA ";
		
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		
		$dadosAgrupados = null;
		$Legenda = "";
		$PosicaoAtual = -1;
		
		while ($row = pg_fetch_array($this->database->result)){
			if($Legenda != $row[1]){
				$PosicaoAtual++;
				$this->dados[] = new DadosAgrupados();				 
				$this->dados[$PosicaoAtual]->SetLegenda($row[1]);
				$this->dados[$PosicaoAtual]->SetId($row[2]);
				$Legenda = $row[1];			 
			}			
			
			$this->dados[$PosicaoAtual]->addDados($row[0]); 
			
			if(!$this->existeLabel($row[3],$this->label)){
				$this->label[] = $row[3];
			}			
		}
				
		$this->label = $this->ordenarLabel($this->label);
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Atividade de chamado
	function KpiChamadosPorAtividade(){
		$sql = "select count(1) as QTD_CHAMADOS, b.DSC_ATIVIDADE_CHAMADO
				FROM gestaoti.chamado a, gestaoti.atividade_chamado b
				where a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= " group by DSC_ATIVIDADE_CHAMADO
				  order by b.DSC_ATIVIDADE_CHAMADO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por Subtipo de chamado
	function KpiChamadosPorSubtipo(){
		$sql = "select count(1) as QTD_CHAMADOS, DSC_SUBTIPO_CHAMADO
				FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				where a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
				and c.seq_tipo_chamado = d.seq_tipo_chamado
				and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sql .= "  and c.SEQ_TIPO_CHAMADO = ".$this->SEQ_TIPO_CHAMADO." ";
		}
		$sql .= " group by DSC_SUBTIPO_CHAMADO
				  order by DSC_SUBTIPO_CHAMADO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = strlen($row[1])>15? substr($row[1],0,15)."..." :$row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de chamados por tipo de chamado
	function KpiChamadosPorTipo(){
		$sql = "select count(1) as QTD_CHAMADOS, DSC_TIPO_CHAMADO
				FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				where a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
				and c.SEQ_TIPO_CHAMADO = d.SEQ_TIPO_CHAMADO
				and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and d.FLG_UTILIZADO_SLA='S'	
				and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}
		$sql .= "
				group by DSC_TIPO_CHAMADO
				order by DSC_TIPO_CHAMADO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = strlen($row[1])>15? substr($row[1],0,15)."..." :$row[1];
		}
	}

	/*
	-----------------------------------------------------------------------------------
	--Visão Gerencial -
	-----------------------------------------------------------------------------------
	*/

	// -----------------------------------------------------------------------------------
	// -- Quantidade de profissionais alocados por equipe
	function KpiQtdProfissionaisPorEquipe(){
		$sql = "select count(1) as QTD, b.NOM_EQUIPE_TI
				FROM gestaoti.recurso_ti a, gestaoti.equipe_ti b
				where a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
				and b.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				group by NOM_EQUIPE_TI
				order by NOM_EQUIPE_TI";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Valor das horas dos profissionais alocados por equipe
	function KpiValorProfissionaisPorEquipe(){
		$sql = "select sum(c.VAL_HORA) as VAL_HORA, b.NOM_EQUIPE_TI
				FROM gestaoti.recurso_ti a, gestaoti.equipe_ti b, gestaoti.perfil_recurso_ti c
				where a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
				and a.SEQ_PERFIL_RECURSO_TI = c.SEQ_PERFIL_RECURSO_TI
				and b.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				group by NOM_EQUIPE_TI
				order by NOM_EQUIPE_TI ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de Sistemas de Informação por Linguagem de Programação
	function KpiQtdSistemasPorLinguagem(){
		$sql = "select count(1) as cont, b.NOM_LINGUAGEM_PROGRAMACAO
				FROM gestaoti.SOFTWARE_LINGUAGEM_PROGRAMACAO a, gestaoti.LINGUAGEM_PROGRAMACAO b
				where a.SEQ_LINGUAGEM_PROGRAMACAO = b.SEQ_LINGUAGEM_PROGRAMACAO
				group by b.NOM_LINGUAGEM_PROGRAMACAO
				order by b.NOM_LINGUAGEM_PROGRAMACAO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Quantidade de Sistemas de Informação por Banco de Dados
	function KpiQtdSistemasPorBancoDeDados(){
		$sql = "select count(1) as cont, b.NOM_BANCO_DE_DADOS
				FROM gestaoti.SOFTWARE_BANCO_DE_DADOS a, gestaoti.BANCO_DE_DADOS b
				where a.SEQ_BANCO_DE_DADOS = b.SEQ_BANCO_DE_DADOS
				group by b.NOM_BANCO_DE_DADOS
				order by b.NOM_BANCO_DE_DADOS ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Profissionais por área de atuação
	function KpiQtdProfissionaisPorAreaAtuacao(){
		$sql = "select count(1) as QTD, b.NOM_AREA_ATUACAO
				FROM gestaoti.recurso_ti a, gestaoti.area_atuacao b, gestaoti.equipe_ti c
				where a.SEQ_AREA_ATUACAO = b.SEQ_AREA_ATUACAO
				and a.SEQ_EQUIPE_TI = c.SEQ_EQUIPE_TI
				and c.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				group by NOM_AREA_ATUACAO
				order by NOM_AREA_ATUACAO ";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Cumprimento do SLA - Chamados encerrados - Porcentagem de chamados encerrados dentro do SLA - Odometro
	function KpiPorcentagemChamadosEncerradosNoPrazo(){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.",".$this->situacao_chamado->COD_Aguardando_Avaliacao.",".$this->situacao_chamado->COD_Cancelado;
		$sql = "select
					  		to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
							a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO,
							to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo
					  FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
					  WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO				  
					  and b.seq_subtipo_chamado = c.seq_subtipo_chamado				  			 			 
					  and c.seq_tipo_chamado = d.seq_tipo_chamado
					  and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
					  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			    and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
					  and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}

		//print $sql;
		$result = $this->database->query($sql);
		$aDadosAux = array();
		$banco = new chamado();
		$cont = 0;
		$contForaPrazo = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			if($v_COD_SLA_ATENDIMENTO == -1){
				$contForaPrazo++;
			}
			$cont++;
		}
		//print "contForaPrazo = $contForaPrazo | cont = $cont | ".($contForaPrazo/$cont)*100;
		if($contForaPrazo > 0){
			$this->valorOdometro = ($contForaPrazo/$cont)*100;
		}else{
			$this->valorOdometro = 0;
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Cumprimento do SLA - Chamados encerrados - Porcentagem de chamados encerrados dentro do SLA - Odometro - Inceidentes
	function KpiPorcentagemChamadosEncerradosNoPrazoIncidente(){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.",".$this->situacao_chamado->COD_Aguardando_Avaliacao.",".$this->situacao_chamado->COD_Cancelado;
		$this->sql = "  select to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM,
					     b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO, b.seq_tipo_ocorrencia, b.qtd_min_sla_solucao_final,
						 to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo,
						 to_char(a.dth_triagem_efetiva, 'dd/mm/yyyy hh24:mi:ss') as dth_triagem_efetiva
				  FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				  WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				  and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
				  and c.seq_tipo_chamado = d.seq_tipo_chamado
				  and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and to_char(dth_triagem_efetiva,'YYYY-MM-DD HH24:MI') <> to_char(dth_encerramento_efetivo,'YYYY-MM-DD HH24:MI')
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			      and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
			      and d.FLG_UTILIZADO_SLA='S'
				  and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO)
				  and b.seq_tipo_ocorrencia = ".$this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE")." ";
		if($this->SEQ_EQUIPE_TI != ""){
			$this->sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}

		//print $this->sql;
		$result = $this->database->query($this->sql);
		$aDadosAux = array();
		$banco = new chamado();
		$cont = 0;
		$contForaPrazo = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);
			if($v_COD_SLA_ATENDIMENTO == -1){
				$contForaPrazo++;
			}
			$cont++;
		}
		//print "contForaPrazo = $contForaPrazo | cont = $cont | ".($contForaPrazo/$cont)*100;
		$this->cont = $cont;
		$this->contForaPrazo = $contForaPrazo;
		if(($cont - $contForaPrazo) > 0){
			$this->valorOdometro = (($cont - $contForaPrazo)/($cont))*100;
		}else{
			$this->valorOdometro = 0;
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Cumprimento do SLA - Chamados encerrados - Porcentagem de chamados encerrados dentro do SLA - Odometro - Inceidentes
	function KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao(){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.",".$this->situacao_chamado->COD_Aguardando_Avaliacao.",".$this->situacao_chamado->COD_Cancelado;
		$this->sql = "  select to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM,
					     b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO, b.seq_tipo_ocorrencia, b.qtd_min_sla_solucao_final,
						 to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo,
						 to_char(a.dth_triagem_efetiva, 'dd/mm/yyyy hh24:mi:ss') as dth_triagem_efetiva
				  FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				  WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				  and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
				  and c.seq_tipo_chamado = d.seq_tipo_chamado
				  and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				  and to_char(dth_triagem_efetiva,'YYYY-MM-DD HH24:MI') <> to_char(dth_encerramento_efetivo,'YYYY-MM-DD HH24:MI')
				  and a.DTH_ABERTURA between to_date('".$this->DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
			      and                         to_date('".$this->DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
			      and d.FLG_UTILIZADO_SLA='S'
				  and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO)
				  and b.seq_tipo_ocorrencia in (".$this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_SOLICITACAO").",
				  								".$this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_DUVIDA").") ";
		if($this->SEQ_EQUIPE_TI != ""){
			$this->sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}

		//print $this->sql;
		$result = $this->database->query($this->sql);
		$aDadosAux = array();
		$banco = new chamado();
		$cont = 0;
		$contForaPrazo = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);
			if($v_COD_SLA_ATENDIMENTO == -1){
				$contForaPrazo++;
			}
			$cont++;
		}
		//print "contForaPrazo = $contForaPrazo | cont = $cont | ".($contForaPrazo/$cont)*100;
		$this->cont = $cont;
		$this->contForaPrazo = $contForaPrazo;
		if(($cont - $contForaPrazo) > 0){
			$this->valorOdometro = (($cont - $contForaPrazo)/($cont))*100;
		}else{
			$this->valorOdometro = 0;
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Cumprimento do SLA - Chamados encerrados - Porcentagem de chamados em andamento dentro do SLA - Odometro
	function KpiPorcentagemChamadosEmAndamentoNoPrazo(){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;
		$sql = "select
			  		to_char(a.DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
					 a.SEQ_CHAMADO, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.FLG_FORMA_MEDICAO_TEMPO,
					to_char(a.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo
				 FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d
				 WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				 and b.seq_subtipo_chamado = c.seq_subtipo_chamado
				 and c.seq_tipo_chamado = d.seq_tipo_chamado
				 and d.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				 and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
	                              FROM gestaoti.atribuicao_chamado
	                              where seq_chamado = a.seq_chamado
	                              and seq_equipe_ti = ".$this->SEQ_EQUIPE_TI.")

					";
		}

		$result = $this->database->query($sql);
		$aDadosAux = array();
		$banco = new chamado();
		$cont = $this->database->rows;
		$contForaPrazo = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//print "$v_COD_SLA_ATENDIMENTO - ".$row["seq_chamado"]." <br>";
			if($v_COD_SLA_ATENDIMENTO != -1){
				$contForaPrazo++;
			}
		}

		if($contForaPrazo > 0){
			//print "<br>($contForaPrazo/$cont)*100<br>";
			$this->valorOdometro = ($contForaPrazo/$cont)*100;
		}else{
			$this->valorOdometro = 0;
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Cumprimento do SLA - Chamados por status de SLA por mês
	function KpiQtdChamadosPorSLAPorMes(){
		$sql = "select count(1) as QTD_CHAMADOS, DSC_SLA_ATENDIMENTO, DTH_ENCERRAMENTO_EFETIVO
				from(
				  SELECT
				         -- Descrição do atendimento do SLA
				        CASE
				          WHEN (a.DTH_ENCERRAMENTO_EFETIVO is not null) THEN
				            CASE
				                WHEN (b.QTD_MIN_SLA_ATENDIMENTO is null) THEN
				                      CASE
				                        WHEN (a.DTH_ENCERRAMENTO_EFETIVO <= (SELECT DTH_PREVISTA
				                                                             FROM gestaoti.aprovacao_chamado
				                                                             where  SEQ_CHAMADO = a.SEQ_CHAMADO
				                                                             and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                                                                                          FROM gestaoti.aprovacao_chamado
				                                                                                          where SEQ_CHAMADO = a.SEQ_CHAMADO))) THEN 'Em Dia'
				                        ELSE 'Atrasado'
				                      END
				                WHEN (a.DTH_ENCERRAMENTO_EFETIVO <= (DTH_ABERTURA + (b.QTD_MIN_SLA_ATENDIMENTO/60/24))) THEN 'Em Dia'
				                ELSE 'Atrasado'
				              END
				          ELSE
				              CASE
				                WHEN (b.QTD_MIN_SLA_ATENDIMENTO is null) THEN
				                      CASE
				                        WHEN (current_date between DTH_ABERTURA and (SELECT DTH_ABERTURA +
				                                                                       to_number((DTH_PREVISTA - DTH_ABERTURA)/4*3)
				                                                                FROM gestaoti.aprovacao_chamado
				                                                                where  SEQ_CHAMADO = a.SEQ_CHAMADO
				                                                                and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                                                                                             FROM gestaoti.aprovacao_chamado
				                                                                                             where SEQ_CHAMADO = a.SEQ_CHAMADO))) THEN 'Em Dia'
				                        WHEN (current_date between (SELECT DTH_ABERTURA +
				                                                      to_number((DTH_PREVISTA - DTH_ABERTURA)/4*3)
				                                               FROM gestaoti.aprovacao_chamado
				                                               where  SEQ_CHAMADO = a.SEQ_CHAMADO
				                                               and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                                                                            FROM gestaoti.aprovacao_chamado
				                                                                            where SEQ_CHAMADO = a.SEQ_CHAMADO))
				                                                                              and (SELECT DTH_PREVISTA
				                                                       FROM gestaoti.aprovacao_chamado
				                                                       where  SEQ_CHAMADO = a.SEQ_CHAMADO
				                                                       and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                                                                                    FROM gestaoti.aprovacao_chamado
				                                                                                    where SEQ_CHAMADO = a.SEQ_CHAMADO))) THEN 'Risco de Atraso'
				                        WHEN (current_date > (SELECT DTH_PREVISTA
				                                         FROM gestaoti.aprovacao_chamado
				                                         where  SEQ_CHAMADO = a.SEQ_CHAMADO
				                                         and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                                                                      FROM gestaoti.aprovacao_chamado
				                                                                      where SEQ_CHAMADO = a.SEQ_CHAMADO))) THEN 'Atrasado'
				                        ELSE 'Não Estabelecido'
				                      END
				                WHEN (current_date between DTH_ABERTURA and (DTH_ABERTURA + (round((b.QTD_MIN_SLA_ATENDIMENTO/4)*3)/60/24))) THEN 'Em Dia'
				                WHEN (current_date between (DTH_ABERTURA + (round((b.QTD_MIN_SLA_ATENDIMENTO/4)*3)/60/24)) and (DTH_ABERTURA + (b.QTD_MIN_SLA_ATENDIMENTO/60/24))) THEN 'Risco de Atraso'
				                WHEN (DTH_ABERTURA + (b.QTD_MIN_SLA_ATENDIMENTO/60/24) < current_date ) THEN 'Atrasado'
				              ELSE 'Não Estabelecido - Erro'
				              END
				        END
				         as DSC_SLA_ATENDIMENTO, a.SEQ_CHAMADO, to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'mm/yyyy') as DTH_ENCERRAMENTO_EFETIVO
				  FROM gestaoti.chamado a, gestaoti.atividade_chamado b
				  WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
				   and a.SEQ_SITUACAO_CHAMADO in (4,8)
				)
				group by DSC_SLA_ATENDIMENTO, DTH_ENCERRAMENTO_EFETIVO
				order by DSC_SLA_ATENDIMENTO, DTH_ENCERRAMENTO_EFETIVO";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}

	// -----------------------------------------------------------------------------------
	// -- Avaliações por mês
	function KpiQtdAvaliacoesPorMes(){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.",".$this->situacao_chamado->COD_Aguardando_Avaliacao.",".$this->situacao_chamado->COD_Cancelado;
		$sql = "select count(1) as QTD_CHAMADOS, NOM_AVALIACAO_ATENDIMENTO, to_char(a.DTH_ENCERRAMENTO_EFETIVO,'mm/yyyy') as MES
				FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d, gestaoti.tipo_chamado e
				where a.SEQ_AVALIACAO_ATENDIMENTO = b.SEQ_AVALIACAO_ATENDIMENTO
				a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
				and c.seq_subtipo_chamado = d.seq_subtipo_chamado
				and d.seq_tipo_chamado = e.seq_tipo_chamado
				and e.seq_central_atendimento = ".$this->SEQ_CENTRAL_ATENDIMENTO."
				and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO)
				group by NOM_AVALIACAO_ATENDIMENTO, to_char(a.DTH_ENCERRAMENTO_EFETIVO,'mm/yyyy')
				order by NOM_AVALIACAO_ATENDIMENTO, to_char(a.DTH_ENCERRAMENTO_EFETIVO,'mm/yyyy')";
		$result = $this->database->query($sql);
		$this->dados = array();
		$this->label = array();
		while ($row = pg_fetch_array($this->database->result)){
			$this->dados[] = $row[0];
			$this->label[] = $row[1];
		}
	}
	
	function existeLabel($label,$labels){
		$existe = false;
		
		for ($y=0; $y < count($labels); $y++){
			if($labels[$y]==$label){
				$existe = true;
				break;
			} 
		}
		
		return $existe;
	}
	
	function ordenarLabel($labels){
		$existe = false;
		$labelAux = array(); 
		
		for ($y=0; $y < count($labels); $y++){
			//$labelAux[$y]= date("d/m/y H:i:s",$labelAux[$y]) ;
			$stssplit = split("/",$labels[$y]);
			$labelAux[$y]= mktime(1,0,0,$stssplit[0],1,$stssplit[1]) ;
		}
		
		sort($labelAux);
		
		for ($y=0; $y < count($labelAux); $y++){
			 $labelAux[$y]= date("d/m/Y H:i:s",$labelAux[$y]) ;			 
		}
		
		$labelDt = null;
		$mes =null;
		$ano = null;
		
		for ($y=0; $y < count($labelAux); $y++){
			$labelDt = split ('[/.-]', $labelAux[$y]);
			 $mes = $labelDt[1];
			 $labelDt =  split (' ', $labelDt[2]);
			 $ano = $labelDt[0];
			 
			 $labelAux[$y]= $mes."/".$ano;
		}
		
		return $labelAux;
	}

} // class : end
?>