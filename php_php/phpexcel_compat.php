<?php
/**
 * Camada de compatibilidade PHPExcel -> PhpSpreadsheet (PHP 8.x).
 *
 * A biblioteca PHPExcel (vendor/phpexcel) esta abandonada e nao carrega no PHP 8
 * (ParseError: sintaxe $texto{0}). Este arquivo reproduz apenas a parte da API que
 * php_php/exportacoes.php usa, delegando para phpoffice/phpspreadsheet (Composer).
 *
 * Diferenca importante: PHPExcel indexava colunas a partir de 0 nos metodos *ByColumn*;
 * o PhpSpreadsheet indexa a partir de 1. O wrapper abaixo soma 1 automaticamente.
 */
require_once __DIR__ . '/../vendor/autoload.php';

if (!class_exists('PHPExcel', false)) {

	class PHPExcel_Style_NumberFormat {
		const FORMAT_TEXT = '@';
	}

	class PHPExcel_Cell_DataType {
		const TYPE_STRING  = 's';
		const TYPE_NUMERIC = 'n';
		const TYPE_BOOL    = 'b';
		const TYPE_FORMULA = 'f';
	}

	/** Planilha: aceita colunas 0-based nos metodos *ByColumn* e repassa o resto. */
	class PHPExcel_Worksheet_Compat {
		private $ws;

		public function __construct(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws) {
			$this->ws = $ws;
		}

		public function setCellValueByColumnAndRow($col, $row, $value) {
			return $this->ws->setCellValueByColumnAndRow((int)$col + 1, $row, $value);
		}

		public function getStyleByColumnAndRow($col1, $row1, $col2 = null, $row2 = null) {
			return $this->ws->getStyleByColumnAndRow(
				(int)$col1 + 1,
				$row1,
				($col2 === null) ? null : (int)$col2 + 1,
				$row2
			);
		}

		public function getColumnDimensionByColumn($col) {
			return $this->ws->getColumnDimensionByColumn((int)$col + 1);
		}

		/** Demais metodos (setCellValue, SetCellValue, getStyle, mergeCells, setTitle, ...). */
		public function __call($name, $args) {
			return $this->ws->$name(...$args);
		}

		public function unwrap() {
			return $this->ws;
		}
	}

	/** Livro. */
	class PHPExcel {
		private $ss;

		public function __construct() {
			$this->ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		}

		public function getActiveSheet() {
			return new PHPExcel_Worksheet_Compat($this->ss->getActiveSheet());
		}

		public function setActiveSheetIndex($i) {
			$this->ss->setActiveSheetIndex($i);
			return $this;
		}

		public function createSheet($i = null) {
			return new PHPExcel_Worksheet_Compat($this->ss->createSheet($i));
		}

		public function getSheetCount() {
			return $this->ss->getSheetCount();
		}

		public function removeSheetByIndex($i) {
			$this->ss->removeSheetByIndex($i);
			return $this;
		}

		public function unwrap() {
			return $this->ss;
		}
	}

	class PHPExcel_Writer_Excel2007 extends \PhpOffice\PhpSpreadsheet\Writer\Xlsx {
		public function __construct($book) {
			parent::__construct($book instanceof PHPExcel ? $book->unwrap() : $book);
		}
	}
}
