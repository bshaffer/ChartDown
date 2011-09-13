<?php

/**
*
*/
class ChartDown_Chart_Transposer
{
    public function __construct()
    {
      $this->noteMap = array(
        ChartDown::A_FLAT  => ChartDown::A_FLAT_VAL,
        ChartDown::A       => ChartDown::A_VAL,
        ChartDown::B_FLAT  => ChartDown::B_FLAT_VAL,
        ChartDown::B       => ChartDown::B_VAL,
        ChartDown::C       => ChartDown::C_VAL,
        ChartDown::C_SHARP => ChartDown::C_SHARP_VAL,
        ChartDown::D       => ChartDown::D_VAL,
        ChartDown::E_FLAT  => ChartDown::E_FLAT_VAL,
        ChartDown::E       => ChartDown::E_VAL,
        ChartDown::F_FLAT  => ChartDown::F_FLAT_VAL,
        ChartDown::F       => ChartDown::F_VAL,
        ChartDown::F_SHARP => ChartDown::F_SHARP_VAL,
        ChartDown::G       => ChartDown::G_VAL,

        // Rarer Matchings
        ChartDown::A_SHARP => ChartDown::A_SHARP_VAL,
        ChartDown::B_SHARP => ChartDown::B_SHARP_VAL,
        ChartDown::D_FLAT  => ChartDown::D_FLAT_VAL,
        ChartDown::D_SHARP => ChartDown::D_SHARP_VAL,
        ChartDown::E_SHARP => ChartDown::E_SHARP_VAL,
        ChartDown::G_FLAT  => ChartDown::G_FLAT_VAL,
        ChartDown::G_SHARP => ChartDown::G_SHARP_VAL,
      );
    }

    public function transposeNote(ChartDown_Chart_Note $note, $interval)
    {
        $value = $this->getValueFromNote($note->getNote());

        $transposedValue = (($value + $interval + 11) % 12) + 1;

        $note->setNote($this->getNoteFromValue($transposedValue));
    }

    public function transposeChord(ChartDown_Chart_Chord $chord, $interval)
    {
        $root = $chord->getRoot();
        $rootNote = $chord->getRootNote();
        $this->transposeNote($rootNote, $interval);

        if ($bass = $chord->getBass()) {
            $bassNote = $chord->getBassNote();
            $this->transposeNote($bassNote, $interval);

            $chord->setText(strtr($chord->getText(), array($root => $rootNote->getNote(), $bass => $bassNote->getNote())));
        } else {
            $chord->setText(str_replace($root, $rootNote->getNote(), $chord->getText()));
        }
    }

    public function getValueFromNote($note, $scale = null)
    {
      return $this->noteMap[$note];
    }

    public function getNoteFromValue($value)
    {
      return array_search($value, $this->noteMap);
    }
}
